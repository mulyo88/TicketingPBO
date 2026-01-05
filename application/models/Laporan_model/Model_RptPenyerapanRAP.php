<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Model_RptPenyerapanRAP extends CI_Model
{
    function dapat_penyerapan_rap($data)
    {
        $JobNo = $data['JobNo'];
        $Alokasi = $data['Alokasi'];
        $dari_tgl = $data['dari_tgl'];
        $sampai_tgl = $data['sampai_tgl'];
        $query = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut");
        return $query->result_array();
    }

    function tes($data)
    {
        $JobNo = $data['JobNo'];
        $Alokasi = $data['Alokasi'];
        $dari_tgl = $data['dari_tgl'];
        $sampai_tgl = $data['sampai_tgl'];
        $query = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' ORDER BY NoUrut");
        return $query->result_array();
    }

    // function dapatkan_total_serap(){
    //     $query = $this->db->query("
    //     SELECT 
    //     (CASE
    //         WHEN a.NoPJ IS NOT NULL THEN (a.PjVol * a.PjHrgSatuan)
    //         ELSE 0
    //     END) as 'apapa'
    //     from PdDtl a 
    //     left join PdHdr b ON a.NoPD=B.NoPD
    //     WHERE b.JobNo='$JobNo' AND b.Alokasi='$Alokasi' AND b.RejectBy IS NULL AND a.KdRAP='$KdRAP' AND b.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl' ")
    // }
}

/* End of file ModelName.php */
