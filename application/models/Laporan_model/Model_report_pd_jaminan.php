<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_report_pd_jaminan extends CI_Model {

	function dapat_data_job($JobNo, $Company){

		$where_job = '';

		if ($JobNo != 'all_job') {
			$where_job = "AND JobNo='$JobNo'";
		}

		return $this->db->query(" SELECT JobNo,JobNm FROM Job WHERE Company='$Company' AND TipeJob='Project' ".$where_job." ORDER BY JobNo DESC ")->result_array();

	}

}

/* End of file Model_report_pd_jaminan.php */
/* Location: ./application/models/Laporan_model/Model_report_pd_jaminan.php */