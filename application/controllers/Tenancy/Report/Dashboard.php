<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load necessary models, libraries, helpers here
        // $this->load->model('Dashboard_model');
    }

    public function index()
    {
        // Example data for dashboard
        $data['judul'] = 'Source Data Report Dashboard';
        $data['bodyclass'] = 'sidebar-collapse skin-black';

        $data['building'] = $this->db->query("select * from tnc_mp_building;")->result();
        $data['Revenue'] = $this->db->query("select * from tnc_Inputrpt;")->result();

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Report/Input_Dashboard', $data);
		$this->load->view('templates/footer');
    }

    public function TambahData()
    {
        // Example data for adding new report
        $data['judul'] = 'Tambah Data Report Dashboard';
        $data['bodyclass'] = 'sidebar-collapse skin-black';

        $data['building'] = $this->db->query("select * from tnc_mp_building;")->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Tenancy/Report/TambahData', $data);
        $this->load->view('templates/footer');
    }

    public function saveData()
    {
        $periode_input = $this->input->post('periode'); // ex: 2025-06
        $periode_sql = $periode_input . '-01'; 

        // Logic to save data from TambahData form
        // $periode = $this->input->post('periode');
        $area = $this->input->post('area');
        $target_rkap_retail = $this->input->post('target_rkap_retail');
        $target_rkap_utility = $this->input->post('target_rkap_utility');
        $achievement_retail = $this->input->post('achievement_retail');
        $achievement_utility = $this->input->post('achievement_utility');

        //calculate total RKAP 
        $jumlah_rkap = $target_rkap_retail + $target_rkap_utility;
        $jumlah_achievement = $achievement_retail + $achievement_utility;


        // Calculate percentages
        $persentase_achievement_retail = ($achievement_retail / $target_rkap_retail) * 100;
        $persentase_achievement_utility = ($achievement_utility / $target_rkap_utility) * 100;
        $persentase_target_vs_achievement = ($jumlah_achievement / $jumlah_rkap) * 100;

        // Prepare data for insertion
        $data = [
            'Periode' => $periode_sql,
            'Area' => $area,
            'RKAP_Retail' => $target_rkap_retail,
            'RKAP_Utility' => $target_rkap_utility,
            'Ach_Retail' => $achievement_retail,
            'Ach_Utility' => $achievement_utility,
            'Jumlah_RKAP' => $jumlah_rkap,
            'Jumlah_Ach' => $jumlah_achievement,
            'Persen_Ach_Retail' => $persentase_achievement_retail,
            'Persen_Ach_Utility' => $persentase_achievement_utility,
            'Persen_TRA' => $persentase_target_vs_achievement,
            'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
            'TimeEntry' => date('Y-m-d H:i:s')
        ];

        // Insert data into database (assuming a table named 'dashboard_reports')
        $this->db->insert('tnc_Inputrpt', $data);

        // Redirect back to dashboard or show success message
        redirect('Tenancy/Report/Dashboard');
    }

    public function UpdateData()
    {
        // Pastikan method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid request method', 405);
        }

        // Ambil data dari form
        $LedgerNo     = $this->input->post('LedgerNo');
        $Area         = $this->input->post('Area', true);
        $Periode      = $this->input->post('Periode', true);
        $RKAP_Retail  = str_replace(',', '', $this->input->post('RKAP_Retail'));
        $RKAP_Utility = str_replace(',', '', $this->input->post('RKAP_Utility'));
        $Ach_Retail   = str_replace(',', '', $this->input->post('Ach_Retail'));
        $Ach_Utility  = str_replace(',', '', $this->input->post('Ach_Utility'));

        // Validasi input wajib
        if (empty($LedgerNo)) {
            $this->session->set_flashdata('error', 'LedgerNo tidak ditemukan.');
            redirect('Tenancy/Report/Dashboard');
        }

        // Data yang akan diupdate
        $data = [
            'Area'         => $Area,
            'Periode'      => $Periode,
            'RKAP_Retail'  => (float)$RKAP_Retail,
            'RKAP_Utility' => (float)$RKAP_Utility,
            'Ach_Retail'   => (float)$Ach_Retail,
            'Ach_Utility'  => (float)$Ach_Utility,
            'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
            'TimeEntry' => date('Y-m-d H:i:s')
        ];

        // Jalankan update
        $this->db->where('LedgerNo', $LedgerNo);
        $update = $this->db->update('tnc_Inputrpt', $data); // ganti nama tabel sesuai tabelmu

        // Beri feedback ke user
        if ($update) {
            $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }

        redirect('Tenancy/Report/Dashboard');
    }

}