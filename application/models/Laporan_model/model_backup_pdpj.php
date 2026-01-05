<?php
defined('BASEPATH') or exit('No direct script access allowed');
// ini_set('memory_limit', '256666M');
// ini_set('sqlsrv.ClientBufferMaxKBSize', '524288666');
// ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288666');
class M_report_querypdpj extends CI_Model
{

    function buat_table_sementara($JobNo, $Alokasi)
    {
        $buat_table_sementara = $this->db->query("
            DROP TABLE IF EXISTS #TempQueryPDPJ
            CREATE TABLE #TempQueryPDPJ (
                LedgerNo bigint identity,
                JobNo ntext,
                JobNm ntext,
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
        SELECT A.JobNo + ' - ' + C.JobNm AS 'JobNo1', C.JobNm,
        A.*, B.SubTotal, B.DiscAmount, B.PPN, 
        (SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
        (SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
        (SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
        (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount'
        FROM PdHdr A 
        LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
        LEFT JOIN Job C ON A.JobNo=C.JobNo 
        WHERE C.Company='MDH' " . $query_job . " ")->result_array();

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

        $query_job = '';
        if ($JobNo <> 'all_job') {
            $query_job .= "AND A.JobNo='$JobNo'";
        }

        if ($Alokasi <> 'all_alokasi') {
            $query_job .= "AND A.Alokasi='$Alokasi'";
        }

        $this->load->library('Datatables');


        $this->datatables->select("
        
        a.JobNo


        ");
        $this->datatables->from('PdHdr a');
        $this->datatables->join('KoHdr b', 'a.NoKO=b.NoKO', 'LEFT');
        $this->datatables->join('Job c', 'a.JobNo=c.JobNo', 'LEFT');
        $this->datatables->where("c.Company='MDH'");


        // $this->datatables->edit_column('TglPD', '$1', 'tgl_baru(TglPD)');
        // $this->datatables->edit_column('TotalKO', '$1', 'number_format(TotalKO)');
        // $this->datatables->edit_column('TotalPD', '$1', 'number_format(TotalPD)');
        // $this->datatables->edit_column('BLEAmount', '$1', 'number_format(BLEAmount)');
        // $this->datatables->edit_column('TglPJ', '$1', 'tgl_baru(TglPJ)');
        // $this->datatables->edit_column('TotalPJ', '$1', 'number_format(TotalPJ)');
        // $this->datatables->edit_column('Saldo', '$1', 'number_format(Saldo)');
        return $this->datatables->generate();
    }

    // function dapat_list_pdpj_json($JobNo, $Alokasi)
    // {
    //     $cek = $this->buat_table_sementara($JobNo, $Alokasi);
    //     if ($cek == 0) {
    //         return 0;
    //         die;
    //     }


    //     $this->load->library('Datatables');
    //     $this->datatables->select("
    //     LedgerNo,
    //     JobNo,
    //     JobNm,
    //     CONCAT_WS(' - ',JobNo,JobNm) as JobGabung,
    //     NoPD,
    //     TglPD,
    //     NoRef,
    //     Alokasi,
    //     TipeForm,
    //     Deskripsi,
    //     NoKO,
    //     TotalKO,
    //     NoTagihan,
    //     NoRek,
    //     Nama,
    //     TotalPD,
    //     TglBayar,
    //     JenisTrf,
    //     BLEAmount,
    //     RekId,
    //     NoPJ,
    //     TglPJ,
    //     TotalPJ,
    //     Saldo


    //     ");
    //     $this->datatables->from('#TempQueryPDPJ');


    //     $this->datatables->edit_column('TglPD', '$1', 'tgl_baru(TglPD)');
    //     $this->datatables->edit_column('TotalKO', '$1', 'number_format(TotalKO)');
    //     $this->datatables->edit_column('TotalPD', '$1', 'number_format(TotalPD)');
    //     $this->datatables->edit_column('BLEAmount', '$1', 'number_format(BLEAmount)');
    //     $this->datatables->edit_column('TglPJ', '$1', 'tgl_baru(TglPJ)');
    //     $this->datatables->edit_column('TotalPJ', '$1', 'number_format(TotalPJ)');
    //     $this->datatables->edit_column('Saldo', '$1', 'number_format(Saldo)');
    //     return $this->datatables->generate();
    // }

    // function dapat_data_querypdpj($JobNo, $Alokasi)
    // {

    //     $query_job = '';
    //     if ($JobNo <> 'all_job') {
    //         $query_job .= "AND A.JobNo='$JobNo'";
    //     }

    //     if ($Alokasi <> 'all_alokasi') {
    //         $query_job .= "AND A.Alokasi='$Alokasi'";
    //     }

    //     $query = $this->db->query("
    //     SELECT  A.JobNo + ' - ' + C.JobNm AS 'JobNo1', A.*, B.SubTotal, B.DiscAmount, B.PPN,
    //     (SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId',
    //     (SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar',
    //     (SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf',
    //     (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount',
    //     C.Company
    //     FROM PdHdr A
    //     LEFT JOIN KoHdr B ON A.NoKO=B.NoKO
    //     LEFT JOIN Job C ON A.JobNo=C.JobNo
    //     WHERE C.Company='MDH' " . $query_job . "

    //     ")->result_array();
    //     return $query;
    // }
}

/* End of file M_report_querypdpj.php */
