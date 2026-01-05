<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_report_outstanding_approvedpd extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        // $this->load->library('Datatables');
    }

    function dapat_list_outstanding_data_json($data)
    {
        $this->load->library('Datatables');

        $this->buat_tbl_sementara_outstanding($data);

        $By_pilihan = $data['By_pilihan'];
        $Type_pilihan = $data['Type_pilihan'];
        $dari_tgl = $data['dari_tgl'];
        $sampai_tgl = $data['sampai_tgl'];

        $this->datatables->select("LedgerNo, JobNo, JobNm, NoPD, Alokasi, TipeForm, AtasNama, NoRek, NoKO, TotalPD, TglPD, DueDate, Deskripsi, CompanyId,  ApprovedBy");
        $this->datatables->from('#TempOutstanding');
        $this->db->order_by('LedgerNo', 'DESC');

        $this->datatables->edit_column('TglPD', '$1', 'tgl_baru(TglPD)');
        $this->datatables->edit_column('DueDate', '$1', 'tgl_baru(DueDate)');
        $this->datatables->edit_column('TotalPD', '$1', 'UbahGabunganUang(TotalPD)');
        return $this->datatables->generate();
    }

    private function buat_tbl_sementara_outstanding($data)
    {

        $By_pilihan = $data['By_pilihan'];
        $Type_pilihan = $data['Type_pilihan'];
        $dari_tgl = $data['dari_tgl'];
        $sampai_tgl = $data['sampai_tgl'];

        $dapatkan_alokasi_sesuai_akses_user = list_job_alokasi();

        $no = 1;
        $kata_or = 'OR';
        $cek_jumlah_alokasi = count($dapatkan_alokasi_sesuai_akses_user);
        $kata_kata = '';

        $sintak_custom = '';

        foreach ($dapatkan_alokasi_sesuai_akses_user as $data_alokasi) :
            $var_alokasi = $data_alokasi['Alokasi'];

            if ($cek_jumlah_alokasi == $no) {
                $kata_or = '';
            }
            $kata_kata .= "a.Alokasi='$var_alokasi' " . $kata_or . " ";

            $no++;
        endforeach;

        $where_alokasi = "AND (" . $kata_kata . ") ";


        if ($By_pilihan == 'PC') {

            if ($Type_pilihan == 'APPROVED') {

                $sintak_custom = "AND a.ApprovedByKT IS NOT NULL AND
                a.TimeApprovedKT >='" . $dari_tgl . "' AND  a.TimeApprovedKT <='" . $sampai_tgl . "' " . $where_alokasi . "  ";
            } else {
                $sintak_custom = "AND (a.ApprovedByAK IS NOT NULL AND 
                a.ApprovedByKT IS NULL AND a.ApprovedByKK IS NULL) AND 
                a.TglPD >='" . $dari_tgl . "' AND a.TglPD <='" . $sampai_tgl . "' " . $where_alokasi . "  ";
            }
        } else {
            if ($Type_pilihan == 'APPROVED') {

                $sintak_custom = "AND a.ApprovedByKK IS NOT NULL AND a.TimeApprovedKK>='" . $dari_tgl . "' AND a.TimeApprovedKK <='" . $sampai_tgl . "'   ";
            } else {

                $sintak_custom = "AND (a.ApprovedByAK IS NOT NULL AND a.ApprovedByKK IS NULL) AND
                a.TglPD>='" . $dari_tgl . "' AND a.TglPD <='" . $sampai_tgl . "'  ";
            }
        }

        $buat_query = $this->db->query("

        DECLARE @By_pilihan nvarchar(20)
        SET @By_pilihan = '$By_pilihan';

        DROP TABLE IF EXISTS #TempOutstanding
        CREATE TABLE #TempOutstanding(
            LedgerNo bigint NOT NULL IDENTITY (1,1),
            JobNo nvarchar(15),
            JobNm nvarchar(100),
            NoPD nvarchar(20),
            Alokasi nvarchar(1),
            TipeForm nvarchar(10),
            AtasNama nvarchar(100),
            NoRek nvarchar(30),
            NoKO nvarchar(15),
            TotalPD money,
            TglPD date,
            DueDate date,
            Deskripsi nvarchar(255),
            CompanyId nvarchar(100),
            ApprovedBy nvarchar(30)
            
        );

        TRUNCATE TABLE #TempOutstanding

        INSERT INTO #TempOutstanding (JobNo,JobNm,NoPD,Alokasi,TipeForm,AtasNama,NoRek,NoKO,TotalPD,TglPD,DueDate,Deskripsi,CompanyId,ApprovedBy)
        SELECT

        a.JobNo, 
        b.JobNm, 
        a.NoPD,
        a.Alokasi,
        a.TipeForm,
        a.AtasNama,
        a.NoRek,
        a.NoKO,
        a.TotalPD,
        a.TglPD,
        (SELECT TOP 1 DueDate FROM Invoice, InvPD WHERE InvPD.NoPD=a.NoPD AND InvPD.NoKO=Invoice.NoKO AND InvPD.InvNo=Invoice.InvNo ORDER BY DueDate DESC) AS 'DueDate',
        a.Deskripsi,
        b.CompanyId,
        (CASE
            WHEN @By_pilihan = 'PC'
                THEN a.ApprovedByKT
            ELSE a.ApprovedByKK
        END) as ApprovedBy
        FROM PdHdr a , Job b
        WHERE a.JobNo=b.JobNo AND
        RejectBy IS NULL AND
        b.Company='MDH' " . $sintak_custom . "
        ");
    }

    function dapat_data_json_outstanding($data)
    {
        $this->buat_tbl_sementara_outstanding($data);

        $this->datatables->select("
        a.LedgerNo,
        a.JobNo, 
        a.JobNm, 
        a.NoPD,
        a.Alokasi,
        a.TipeForm,
        a.AtasNama,
        a.NoRek,
        a.NoKO,
        a.TotalPD,
        a.TglPD,
        a.DueDate,
        a.CompanyId,
        a.ApprovedBy
        
        ");

        $this->datatables->from('#TempOutstanding a');
        return $this->datatables->generate();
    }

    function dapat_data_outstanding($data){
        $this->buat_tbl_sementara_outstanding($data);
        return $this->db->query("
            SELECT LedgerNo, JobNo, JobNm, NoPD, Alokasi, TipeForm, AtasNama, NoRek, NoKO, TotalPD, TglPD, DueDate, Deskripsi, CompanyId,  ApprovedBy FROM #TempOutstanding ORDER BY LedgerNo DESC  ")->result_array();
    }

    // function dapat_data_outstanding($data){
    //     $By_pilihan = $data['By_pilihan'];
    //     $Type_pilihan = $data['Type_pilihan'];
    //     $dari_tgl = $data['dari_tgl'];
    //     $sampai_tgl = $data['sampai_tgl'];

    //     $dapatkan_alokasi_sesuai_akses_user = list_job_alokasi();

    //     $no = 1;
    //     $kata_or = 'OR';
    //     $cek_jumlah_alokasi = count($dapatkan_alokasi_sesuai_akses_user);
    //     $kata_kata = '';

    //     $sintak_custom = '';

    //     foreach ($dapatkan_alokasi_sesuai_akses_user as $data_alokasi) :
    //         $var_alokasi = $data_alokasi['Alokasi'];

    //         if ($cek_jumlah_alokasi == $no) {
    //             $kata_or = '';
    //         }
    //         $kata_kata .= "a.Alokasi='$var_alokasi' " . $kata_or . " ";

    //         $no++;
    //     endforeach;

    //     $where_alokasi = "AND (" . $kata_kata . ") ";


    //     if ($By_pilihan == 'PC') {

    //         if ($Type_pilihan == 'APPROVED') {

    //             $sintak_custom = "AND a.ApprovedByKT IS NOT NULL AND
    //             a.TimeApprovedKT >='" . $dari_tgl . "' AND  a.TimeApprovedKT <='" . $sampai_tgl . "' " . $where_alokasi . "  ";
    //         } else {
    //             $sintak_custom = "AND (a.ApprovedByAK IS NOT NULL AND 
    //             a.ApprovedByKT IS NULL AND a.ApprovedByKK IS NULL) AND 
    //             a.TglPD >='" . $dari_tgl . "' AND a.TglPD <='" . $sampai_tgl . "' " . $where_alokasi . "  ";
    //         }
    //     } else {
    //         if ($Type_pilihan == 'APPROVED') {

    //             $sintak_custom = "AND a.ApprovedByKK IS NOT NULL AND a.TimeApprovedKK>='" . $dari_tgl . "' AND a.TimeApprovedKK <='" . $sampai_tgl . "'   ";
    //         } else {

    //             $sintak_custom = "AND (a.ApprovedByAK IS NOT NULL AND a.ApprovedByKK IS NULL) AND
    //             a.TglPD>='" . $dari_tgl . "' AND a.TglPD <='" . $sampai_tgl . "'  ";
    //         }
    //     }

    //     $buat_query = $this->db->query("

    //     DECLARE @By_pilihan nvarchar(20)
    //     SET @By_pilihan = '$By_pilihan';

    //     DROP TABLE IF EXISTS #TempOutstanding
    //     CREATE TABLE #TempOutstanding(
    //         LedgerNo bigint NOT NULL IDENTITY (1,1),
    //         JobNo nvarchar(15),
    //         JobNm nvarchar(100),
    //         NoPD nvarchar(20),
    //         Alokasi nvarchar(1),
    //         TipeForm nvarchar(10),
    //         AtasNama nvarchar(100),
    //         NoRek nvarchar(30),
    //         NoKO nvarchar(15),
    //         TotalPD money,
    //         TglPD date,
    //         DueDate date,
    //         Deskripsi nvarchar(255),
    //         CompanyId nvarchar(100),
    //         ApprovedBy nvarchar(30)
            
    //     );

    //     TRUNCATE TABLE #TempOutstanding

    //     INSERT INTO #TempOutstanding (JobNo,JobNm,NoPD,Alokasi,TipeForm,AtasNama,NoRek,NoKO,TotalPD,TglPD,DueDate,Deskripsi,CompanyId,ApprovedBy)
    //     SELECT

    //     a.JobNo, 
    //     b.JobNm, 
    //     a.NoPD,
    //     a.Alokasi,
    //     a.TipeForm,
    //     a.AtasNama,
    //     a.NoRek,
    //     a.NoKO,
    //     a.TotalPD,
    //     a.TglPD,
    //     (SELECT TOP 1 DueDate FROM Invoice, InvPD WHERE InvPD.NoPD=a.NoPD AND InvPD.NoKO=Invoice.NoKO AND InvPD.InvNo=Invoice.InvNo ORDER BY DueDate DESC) AS 'DueDate',
    //     a.Deskripsi,
    //     b.CompanyId,
    //     (CASE
    //         WHEN @By_pilihan = 'PC'
    //             THEN a.ApprovedByKT
    //         ELSE a.ApprovedByKK
    //     END) as ApprovedBy
    //     FROM PdHdr a , Job b
    //     WHERE a.JobNo=b.JobNo AND
    //     RejectBy IS NULL AND
    //     b.Company='MDH' " . $sintak_custom . "
    //     ");



    // }

    // function buat_total_pd($data)
    // {
    //     $By_pilihan = $data['By_pilihan'];
    //     $Type_pilihan = $data['Type_pilihan'];
    //     $dari_tgl = $data['dari_tgl'];
    //     $sampai_tgl = $data['sampai_tgl'];
    // }


    // function dapat_total_pd(){
    //     $this->db->query("select SUM(TotalPD) as SubTotalPD from #TempOutstanding")
    // }
}

/* End of file M_report_outstanding_approvedpd.php */
