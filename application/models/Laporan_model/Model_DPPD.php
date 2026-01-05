<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_DPPD extends CI_Model
{
	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	function dapat_data_dppd($JobNo, $Alokasi, $dari_tgl = '', $sampai_tgl = '', $all_periode)
	{

		$validasi_tgl = NULL;

		if ($all_periode == 1) {
			$dari_tgl = '';
			$sampai_tgl = '';
		}

		if ($dari_tgl !== '' and $sampai_tgl !== '') {
			$validasi_tgl = " AND TglPD BETWEEN '" . $dari_tgl . "' AND '" . $sampai_tgl . "'";
		}


		$query = $this->db->query(
			"
			SELECT  A.JobNo AS JobNo, A.*, B.JobNm,

			(SELECT Amount from BLE WHERE NoPD=A.NoPD) AS 'TotalBayar',
			(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
			(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
			(SELECT TOP 1 CaraBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'CaraBayar' 
			FROM PdHdr A 
			LEFT JOIN Job B ON A.JobNo=B.JobNo WHERE 
			A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' " . $validasi_tgl . "  ORDER BY NoPD DESC

			"
		);

		return $query;
	}

	function dapat_data_dppd_lama($data)
	{
		$JobNo = $data['JobNo'];
		$Alokasi = $data['Alokasi'];
		$PrdAwal = $data['dari_tgl'];
		$PrdAkhir = $data['sampai_tgl'];
		$all_periode = $data['all_periode'];

		$query_tgl = '';

		if ($PrdAwal != '' || $PrdAkhir != '') {
			$query_tgl = "AND TglPD BETWEEN '$PrdAwal' AND '$PrdAkhir'";
		}


		$query = $this->db->query("
        	SELECT A.JobNo AS JobNo, A.*, B.JobNm,
        	(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId',
        	(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar',
        	(SELECT TOP 1 CaraBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'CaraBayar'
        	FROM PdHdr A
        	LEFT JOIN Job B ON A.JobNo=B.JobNo 
        	WHERE A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' " . $query_tgl . " ORDER BY NoPD DESC ");

		return $query->result_array();
	}
}

/* End of file Model_DPPD.php */
/* Location: ./application/models/Laporan_model/Model_DPPD.php */