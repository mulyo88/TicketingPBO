<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ControlTenancyData extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
    }

    public function getFilteredBulanBerjalan()
    {
        if (!$this->input->is_ajax_request() || !$this->input->post('tahun') || !$this->input->post('bulan')) {
            echo json_encode(['status' => 'error', 'message' => 'something went wrong']);
        }

        $bulan = trim($this->input->post('bulan'));
        $tahun = trim($this->input->post('tahun'));

        $revenue = $this->getDataTenancyFiltered($bulan, $tahun);

        echo json_encode(['revenue' => $revenue]);
    }

    private function getDataTenancyFiltered($bulan, $tahun)
    {
        return $this->db
            ->select("
            Area,
            SUM(RKAP_Retail) AS RKAP_Retail,
            SUM(RKAP_Utility) AS RKAP_Utility,
            SUM(Ach_Retail) AS Ach_Retail,
            SUM(Ach_Utility) AS Ach_Utility,
            SUM(Jumlah_RKAP) AS Jumlah_RKAP,
            SUM(Jumlah_Ach) AS Jumlah_Ach,
            (SUM(Jumlah_Ach) / NULLIF(SUM(Jumlah_RKAP), 0)) * 100 AS Persen_TRA
        ", false)
            ->from('tnc_Inputrpt')
            ->where('MONTH(Periode)', $bulan)
            ->where('YEAR(Periode)', $tahun)
            ->group_by('Area')
            ->order_by("
            CASE 
                WHEN Area = 'Sosoro Mall & Hotel' THEN 1
                WHEN Area = 'Anjungan Agung Mall' THEN 2
                WHEN Area = 'Plaza Marina Labuan Bajo' THEN 3
                ELSE 4
            END
        ", 'ASC')
            ->get()
            ->result_array();
    }

    public function getFilteredYTD()
    {
        if (!$this->input->is_ajax_request() || !$this->input->post('tahun')) {
            echo json_encode(['status' => 'error', 'message' => 'something went wrong']);
            return;
        }

        $tahun = trim($this->input->post('tahun'));

        // Dapatkan bulan berjalan (hingga Oktober jika tahun berjalan)
        $current_year = date('Y');
        $current_month = date('n') - 1;
        // echo $current_month;
        // die;


        if ($tahun == $current_year) {
            // Jika tahun berjalan, filter hingga Oktober
            $end_month = ($current_month > 10) ? 10 : $current_month;
        } else {
            // Jika tahun sebelumnya, filter hingga Oktober
            $end_month = 10;
        }

        $this->db
            ->select("
                Area,
                SUM(RKAP_Retail) AS RKAP_Retail,
                SUM(RKAP_Utility) AS RKAP_Utility,
                SUM(Ach_Retail) AS Ach_Retail,
                SUM(Ach_Utility) AS Ach_Utility,
                SUM(Jumlah_RKAP) AS Jumlah_RKAP,
                SUM(Jumlah_Ach) AS Jumlah_Ach,
                (SUM(Ach_Retail) / NULLIF(SUM(RKAP_Retail),0)) * 100 AS Persen_Ach_Retail,
                (SUM(Ach_Utility) / NULLIF(SUM(RKAP_Utility),0)) * 100 AS Persen_Ach_Utility,
                (SUM(Jumlah_Ach) / NULLIF(SUM(Jumlah_RKAP),0)) * 100 AS Persen_TRA
            ", false)
            ->from('tnc_Inputrpt')
            ->where('YEAR(Periode)', $tahun)
            ->where('MONTH(Periode) BETWEEN 1 AND ' . $end_month)
            ->group_by('Area')
            ->order_by("
                CASE 
                    WHEN Area = 'Sosoro Mall & Hotel' THEN 1
                    WHEN Area = 'Anjungan Agung Mall' THEN 2
                    WHEN Area = 'Plaza Marina Labuan Bajo' THEN 3
                    ELSE 4
                END
            ", 'ASC');

        $query = $this->db->get()->result_array();

        // Tambahkan informasi bulan filter dalam response
        $response = [
            'revenue' => $query,
            'filter_info' => [
                'tahun' => $tahun,
                'bulan_awal' => 1,
                'bulan_akhir' => $end_month,
                'total_bulan' => $end_month
            ]
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    // ==============================
    function panggilTenant()
    {
        if (!$this->input->post('segmen') || !$this->input->post('tahun')) {
            echo json_encode(['status' => 'error', 'message' => 'Silakan pilih segmen dan tahun']);
            die;
        }

        $segmen = $this->sanitize_input($this, 'segmen');
        $tahun = $this->sanitize_input($this, 'tahun');

        if (empty($segmen) || empty($tahun)) {
            echo json_encode(['status' => 'error', 'message' => 'Silakan pilih segmen dan tahun']);
            die;
        }

        $data = $this->getSemuaDataTenant($tahun);
        $chart_data = $this->prepare_chart_data($data);

        // Tambahkan filter info untuk ditampilkan di chart
        $chart_data['filter_info'] = [
            'tahun' => $tahun,
            'segmen' => $segmen,
            'bulan_akhir' => $this->get_bulan_akhir($tahun)
        ];

        header('Content-Type: application/json');
        echo json_encode($chart_data);
    }

    private function prepare_chart_data($data)
    {
        // Gabungkan kategori:
        // - Hotel dan Meeting Room menjadi HotelMeeting
        // - Utility dan Parkir menjadi UtilityParkir
        $categories = ['AlihDaya', 'PSC', 'HotelMeeting', 'Komersial', 'UtilityParkir'];
        $series = [];

        foreach ($categories as $category) {
            if ($category === 'HotelMeeting') {
                // Gabungkan data Hotel dan Meeting Room
                $rkap = ($data['RKAP_YTD_Hotel'] ?? 0) + ($data['RKAP_YTD_MeetingRoom'] ?? 0);
                $prognosa = ($data['Prognos_YTD_Hotel'] ?? 0) + ($data['Prognos_YTD_MeetingRoom'] ?? 0);
                $realisasi = ($data['real_YTD_Hotel'] ?? 0) + ($data['real_YTD_MeetingRoom'] ?? 0);
            } else if ($category === 'UtilityParkir') {
                // Gabungkan data Utility dan Parkir
                $rkap = ($data['RKAP_YTD_Utility'] ?? 0) + ($data['RKAP_YTD_Parkir'] ?? 0);
                $prognosa = ($data['Prognos_YTD_Utility'] ?? 0) + ($data['Prognos_YTD_Parkir'] ?? 0);
                $realisasi = ($data['real_YTD_Utility'] ?? 0) + ($data['real_YTD_Parkir'] ?? 0);
            } else {
                $rkap = $data['RKAP_YTD_' . $category] ?? 0;
                $prognosa = $data['Prognos_YTD_' . $category] ?? 0;
                $realisasi = $data['real_YTD_' . $category] ?? 0;
            }

            // Hitung achievement
            $achievement_rkap = $this->safe_divide($realisasi, $rkap) * 100;
            $achievement_prognosa = $this->safe_divide($realisasi, $prognosa) * 100;

            $series['RKAP'][] = floatval($rkap);
            $series['Prognosa'][] = floatval($prognosa);
            $series['Realisasi'][] = floatval($realisasi);
            $series['Achievement_RKAP'][] = floatval($achievement_rkap);
            $series['Achievement_Prognosa'][] = floatval($achievement_prognosa);
        }

        return [
            'categories' => $categories,
            'series' => $series,
            'original_data' => $data // Simpan data original untuk referensi
        ];
    }

    public function getSemuaDataTenant($tahun)
    {
        $this->db->select("
            -- Alih Daya
            SUM(CASE WHEN KdInvest = '6.1.1.11' THEN RKAP END) as RKAP_YTD_AlihDaya,
            SUM(CASE WHEN KdInvest = '6.1.1.11' AND Bulan < " . $this->get_bulan_akhir($tahun) . " THEN Realisasi ELSE 0 END) + 
            SUM(CASE WHEN KdInvest = '6.1.1.11' AND Bulan = " . $this->get_bulan_akhir($tahun) . " THEN Prognosa ELSE 0 END) as Prognos_YTD_AlihDaya,
            SUM(CASE WHEN KdInvest = '6.1.1.11' THEN Realisasi END) as real_YTD_AlihDaya,
            
            -- PSC
            SUM(CASE WHEN KdInvest = '6.1.1.3' THEN RKAP END) as RKAP_YTD_PSC,
            SUM(CASE WHEN KdInvest = '6.1.1.3' AND Bulan < " . $this->get_bulan_akhir($tahun) . " THEN Realisasi ELSE 0 END) + 
            SUM(CASE WHEN KdInvest = '6.1.1.3' AND Bulan = " . $this->get_bulan_akhir($tahun) . " THEN Prognosa ELSE 0 END) as Prognos_YTD_PSC,
            SUM(CASE WHEN KdInvest = '6.1.1.3' THEN Realisasi END) as real_YTD_PSC,
            
            -- Hotel
            SUM(CASE WHEN KdInvest = '6.1.1.5' THEN RKAP END) as RKAP_YTD_Hotel,
            SUM(CASE WHEN KdInvest = '6.1.1.5' AND Bulan < " . $this->get_bulan_akhir($tahun) . " THEN Realisasi ELSE 0 END) + 
            SUM(CASE WHEN KdInvest = '6.1.1.5' AND Bulan = " . $this->get_bulan_akhir($tahun) . " THEN Prognosa ELSE 0 END) as Prognos_YTD_Hotel,
            SUM(CASE WHEN KdInvest = '6.1.1.5' THEN Realisasi END) as real_YTD_Hotel,
            
            -- Meeting Room
            SUM(CASE WHEN KdInvest = '6.1.1.6' THEN RKAP END) as RKAP_YTD_MeetingRoom,
            SUM(CASE WHEN KdInvest = '6.1.1.6' AND Bulan < " . $this->get_bulan_akhir($tahun) . " THEN Realisasi ELSE 0 END) + 
            SUM(CASE WHEN KdInvest = '6.1.1.6' AND Bulan = " . $this->get_bulan_akhir($tahun) . " THEN Prognosa ELSE 0 END) as Prognos_YTD_MeetingRoom,
            SUM(CASE WHEN KdInvest = '6.1.1.6' THEN Realisasi END) as real_YTD_MeetingRoom,
            
            -- Komersial
            SUM(CASE WHEN KdInvest = '6.1.1.1' THEN RKAP END) as RKAP_YTD_Komersial,
            SUM(CASE WHEN KdInvest = '6.1.1.1' AND Bulan < " . $this->get_bulan_akhir($tahun) . " THEN Realisasi ELSE 0 END) + 
            SUM(CASE WHEN KdInvest = '6.1.1.1' AND Bulan = " . $this->get_bulan_akhir($tahun) . " THEN Prognosa ELSE 0 END) as Prognos_YTD_Komersial,
            SUM(CASE WHEN KdInvest = '6.1.1.1' THEN Realisasi END) as real_YTD_Komersial,
            
            -- Parkir
            SUM(CASE WHEN KdInvest = '6.1.1.2' THEN RKAP END) as RKAP_YTD_Parkir,
            SUM(CASE WHEN KdInvest = '6.1.1.2' AND Bulan < " . $this->get_bulan_akhir($tahun) . " THEN Realisasi ELSE 0 END) + 
            SUM(CASE WHEN KdInvest = '6.1.1.2' AND Bulan = " . $this->get_bulan_akhir($tahun) . " THEN Prognosa ELSE 0 END) as Prognos_YTD_Parkir,
            SUM(CASE WHEN KdInvest = '6.1.1.2' THEN Realisasi END) as real_YTD_Parkir,
            
            -- Utility
            SUM(CASE WHEN KdInvest = '6.1.1.10' THEN RKAP END) as RKAP_YTD_Utility,
            SUM(CASE WHEN KdInvest = '6.1.1.10' AND Bulan < " . $this->get_bulan_akhir($tahun) . " THEN Realisasi ELSE 0 END) + 
            SUM(CASE WHEN KdInvest = '6.1.1.10' AND Bulan = " . $this->get_bulan_akhir($tahun) . " THEN Prognosa ELSE 0 END) as Prognos_YTD_Utility,
            SUM(CASE WHEN KdInvest = '6.1.1.10' THEN Realisasi END) as real_YTD_Utility
        ");

        $this->db->from('FA_InputKonsol');
        $this->db->where_in('KdInvest', array('6.1.1.11', '6.1.1.3', '6.1.1.5', '6.1.1.6', '6.1.1.1', '6.1.1.2', '6.1.1.10'));
        $this->db->where('Tahun', $tahun);
        $this->db->where('Bulan >=', 1);
        $this->db->where('Bulan <=', $this->get_bulan_akhir($tahun));

        $query = $this->db->get();
        return $query->row_array();
    }

    private function get_bulan_akhir($tahun)
    {
        $bulan_sekarang = date('n');
        $tahun_sekarang = date('Y');

        $bulan_akhir = $bulan_sekarang - 1;

        if ($bulan_akhir < 1) {
            $bulan_akhir = 1;
        }

        if ($tahun != $tahun_sekarang) {
            $bulan_akhir = 12;
        }

        return $bulan_akhir;
    }

    // Helper function untuk menghindari division by zero
    public function safe_divide($numerator, $denominator, $default = 0)
    {
        if ($denominator == 0 || $denominator == null) {
            return $default;
        }
        return $numerator / $denominator;
    }

    public function format_number($value, $decimals = 2)
    {
        if ($value === null || $value === '') {
            return 0;
        }
        return number_format(floatval($value), $decimals, ',', '.');
    }

    function sanitize_input($ci, $key)
    {
        $input = $ci->input->post($key, TRUE); // XSS filter
        return trim(preg_replace('/\s+/', ' ', $input)); // Hapus spasi berlebih
    }
}
