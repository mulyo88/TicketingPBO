<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_report_querypdpj extends CI_Model
{
    var $Company;
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->Company = $this->config->item('Company');

    }

    private function insert_ke_tbl_querypdpj($JobNo, $Alokasi)
    {

        $query_job = '';
        if ($JobNo <> 'all_job') {
            $query_job .= " AND A.JobNo='$JobNo'";
        }


        if ($Alokasi <> 'all_alokasi') {
            $query_job .= " AND A.Alokasi='$Alokasi'";
        }

        $this->db->query("
        TRUNCATE TABLE Tbl_report_querypdpj
        INSERT INTO Tbl_report_querypdpj (JobNo,NoPD,TglPD,NoRef,Alokasi,TipeForm,Deskripsi,NoKO,NoTagihan,NoRek,AtasNama,TotalPD,TglBayar,JenisTrf,BLEAmount,RekId,NoPJ,TglPJ,TotalPJ,Saldo,Company)
        SELECT A.JobNo, A.NoPD, A.TglPD, A.NoRef, A.Alokasi, A.TipeForm, A.Deskripsi, A.NoKO, A.NoTagihan, A.NoRek, A.AtasNama, A.TotalPD,
        (SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
        (SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
        (SELECT ISNULL(SUM(Amount), 0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount', 
        (SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
        A.NoPJ, 
        A.TglPJ, 
        A.TotalPJ,
        NULL,
        'KIP'
        FROM PdHdr A
        LEFT JOIN KoHdr B ON A.NoKO=B.NoKO
        LEFT JOIN Job C ON A.JobNo=C.JobNo
        WHERE C.Company = '$this->Company' " . $query_job . "");
    }
    function get_data_query_pdpj_json($JobNo, $Alokasi)
    {


        $this->load->library('Datatables');
        $this->insert_ke_tbl_querypdpj($JobNo, $Alokasi);

        $this->session->set_flashdata('GrandTotal_PD', $this->GrandTotal_PD());
        $this->session->set_flashdata('GrandTotal_Pembayaran', $this->GrandTotal_Pembayaran());
        $this->session->set_flashdata('GrandTotal_PJ', $this->GrandTotal_PJ());

        $this->datatables->select("
        LedgerNo,
        JobNo,
        NoPD,
        TglPD,
        NoRef,
        Alokasi,
        TipeForm,
        Deskripsi,
        NoKO,
        NoTagihan,
        NoRek,
        AtasNama,
        TotalPD,
        TglBayar,
        JenisTrf,
        BLEAmount,
        RekId,
        NoPJ,
        TglPJ,
        TotalPJ");
        $this->datatables->from('Tbl_report_querypdpj');
        $this->datatables->edit_column('TglPD', '$1', 'tgl_baru(TglPD)');
        // $this->datatables->edit_column('TotalPD', '$1', 'number_format(TotalPD)');
        // $this->datatables->edit_column('TglBayar', '$1', 'tgl_baru(TglBayar)');
        // $this->datatables->edit_column('BLEAmount', '$1', 'UbahGabunganUang(BLEAmount)');
        $this->datatables->edit_column('TglPJ', '$1', 'tgl_baru(TglPJ)');
        // $this->datatables->edit_column('TotalPJ', '$1', 'number_format(TotalPJ)');
        // $this->db->order_by('LedgerNo', 'DESC');
        return $this->datatables->generate();
    }

    function get_pdf_query_pdpj($JobNo, $Alokasi)
    {
        return $this->db->order_by('LedgerNo', 'DESC')->get('Tbl_report_querypdpj')->result_array();
    }


    // batas

    function buat_table_sementara($JobNo, $Alokasi)
    {
        $buat_table_sementara = $this->db->query("
            DROP TABLE IF EXISTS #TempQueryPDPJ
            CREATE TABLE #TempQueryPDPJ (
                LedgerNo bigint identity,
                JobNo nvarchar(15),
                JobNm nvarchar(100),
                NoPD nvarchar(50),
                TglPD date,
                NoRef nvarchar(50),
                Alokasi nvarchar(50),
                TipeForm nvarchar(50),
                Deskripsi nvarchar(255),
                NoKO nvarchar(15),
                TotalKO money,
                NoTagihan nvarchar(100),
                NoRek nvarchar(30),
                Nama nvarchar(100),
                TotalPD money,
                TglBayar date,
                JenisTrf nvarchar(50),
                BLEAmount money,
                RekId ntext,
                NoPJ nvarchar(50),
                TglPJ date,
                TotalPJ money,
                Saldo money
                
            );
        ");
        return $this->isi_table_sementara($JobNo, $Alokasi);
    }


    private function isi_table_sementara($JobNo, $Alokasi)
    {
        $query_job = '';
        if ($JobNo <> 'all_job') {
            $query_job .= "AND A.JobNo='$JobNo'";
        }

        if ($Alokasi <> 'all_alokasi') {
            $query_job .= "AND A.Alokasi='$Alokasi'";
        }


        $query_data_pdpj = $this->db->query("
        SELECT TOP 100 A.JobNo + ' - ' + C.JobNm AS 'JobNo1', C.JobNm,
        A.*, B.SubTotal, B.DiscAmount, B.PPN, 
        (SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
        (SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
        (SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
        (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount'
        FROM PdHdr A 
        LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
        LEFT JOIN Job C ON A.JobNo=C.JobNo 
        WHERE C.Company='$this->Company' " . $query_job . " ")->result_array();

        $isi_data = array();
        foreach ($query_data_pdpj as $row) :
            $isi_data[] = array(
                'JobNo' => $row['JobNo'],
                'JobNm' => $row['JobNm'],
                'NoPD' => $row['NoPD'],
                'TglPD' => $row['TglPD'],
                'NoRef' => $row['NoRef'],
                'Alokasi' => $row['Alokasi'],
                'TipeForm' => $row['TipeForm'],
                'Deskripsi' => $row['Deskripsi'],
                'NoKO' => $row['NoKO'],
                'TotalKO' => 0,
                'NoTagihan' => $row['NoTagihan'],
                'NoRek' => $row['NoRek'],
                'Nama' => $row['Nama'],
                'TotalPD' => $row['TotalPD'],
                'TglBayar' => $row['TglBayar'],
                'JenisTrf' => $row['JenisTrf'],
                'BLEAmount' => $row['BLEAmount'],
                'RekId' => $row['RekId'],
                'NoPJ' => $row['NoPJ'],
                'TglPJ' => $row['TglPJ'],
                'TotalPJ' => $row['TotalPJ'],
                'Saldo' => $row['Saldo'],

            );
        endforeach;
        $this->db->insert_batch('#TempQueryPDPJ', $isi_data);
        return $this->db->affected_rows();
    }



    function dapat_list_pdpj_json($JobNo, $Alokasi)
    {
        $cek = $this->buat_table_sementara($JobNo, $Alokasi);
        if ($cek == 0) {
            return 0;
            die;
        }


        $this->load->library('Datatables');
        $this->datatables->select("
        LedgerNo,
        JobNo,
        JobNm,
        CONCAT_WS(' - ',JobNo,JobNm) as JobGabung,
        NoPD,
        TglPD,
        NoRef,
        Alokasi,
        TipeForm,
        Deskripsi,
        NoKO,
        TotalKO,
        NoTagihan,
        NoRek,
        Nama,
        TotalPD,
        TglBayar,
        JenisTrf,
        BLEAmount,
        RekId,
        NoPJ,
        TglPJ,
        TotalPJ,
        Saldo


        ");
        $this->datatables->from('#TempQueryPDPJ');


        $this->datatables->edit_column('TglPD', '$1', 'tgl_baru(TglPD)');
        $this->datatables->edit_column('TotalKO', '$1', 'number_format(TotalKO)');
        $this->datatables->edit_column('TotalPD', '$1', 'number_format(TotalPD)');
        $this->datatables->edit_column('BLEAmount', '$1', 'number_format(BLEAmount)');
        $this->datatables->edit_column('TglPJ', '$1', 'tgl_baru(TglPJ)');
        $this->datatables->edit_column('TotalPJ', '$1', 'number_format(TotalPJ)');
        $this->datatables->edit_column('Saldo', '$1', 'number_format(Saldo)');
        return $this->datatables->generate();
    }

    function get_data_pdpj($JobNo, $Alokasi)
    {
        $query_job = '';
        if ($JobNo <> 'all_job') {
            $query_job .= "AND A.JobNo='$JobNo'";
        }

        if ($Alokasi <> 'all_alokasi') {
            $query_job .= "AND A.Alokasi='$Alokasi'";
        }


        $query_data_pdpj = $this->db->query("
        SELECT TOP 100 A.JobNo + ' - ' + C.JobNm AS 'JobNo1', C.JobNm,
        A.*, B.SubTotal, B.DiscAmount, B.PPN, 
        (SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
        (SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
        (SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
        (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount'
        FROM PdHdr A 
        LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
        LEFT JOIN Job C ON A.JobNo=C.JobNo 
        WHERE C.Company='$this->Company' " . $query_job . " ")->result_array();

        return $query_data_pdpj;
    }

    private function GrandTotal_PD($LedgerNo = '')
    {
        $query = $this->db->query("SELECT SUM(TotalPD) as GrandTotal_PD FROM Tbl_report_querypdpj ")->row_array();
        return $query['GrandTotal_PD'];
    }

    private function GrandTotal_Pembayaran($LedgerNo = '')
    {
        $query = $this->db->query("SELECT SUM(BLEAmount) as GrandTotal_Pembayaran FROM Tbl_report_querypdpj ")->row_array();
        return $query['GrandTotal_Pembayaran'];
    }

    private function GrandTotal_PJ($LedgerNo = '')
    {
        $query = $this->db->query("SELECT SUM(TotalPJ) as GrandTotal_PJ FROM Tbl_report_querypdpj ")->row_array();
        return $query['GrandTotal_PJ'];
    }


    function get_data_untuk_pdf($JobNo, $Alokasi, $LedgerNo = '')
    {
        $query_job = '';
        $mypilih = '';
        if ($JobNo <> 'all_job') {
            $query_job .= "AND JobNo='$JobNo'";
        }

        if ($Alokasi <> 'all_alokasi') {
            $query_job .= "AND Alokasi='$Alokasi'";
        }


        if ($LedgerNo <> '') {
            $mypilih = "AND LedgerNo IN (" . $LedgerNo . ")";
        }

        return $this->db->query("SELECT * FROM Tbl_report_querypdpj WHERE Company='$this->Company' " . $query_job . " " . $mypilih . "  ")->result_array();
    }
}

/* End of file M_report_querypdpj.php */
