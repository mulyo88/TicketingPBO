<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GetAjax extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array("M_job"));
		$this->load->model(array("M_getAjax"));
		$this->load->helper(array('form', 'url', 'racode'));
	}

	///Domain YAD 

	function json_YAD($JobNo)
	{
		header('Content-Type: application/json');
		echo $this->M_getAjax->Get_json_YAD($JobNo);
	}

	public function GetFormYAD()
	{
		if (isset($_POST)) {
			$type = $this->input->post('type', TRUE);
			$LedgerNo = $this->input->post('LedgerNo', TRUE);
			$JobNo = $this->input->post('JobNo', TRUE);
			// $JobNm = $this->input->post('JobNm', TRUE);
			// if ($type == 'Tambah') {
			// 	$data['JobNo'] = $JobNo;
			// 	$data['JobNm'] = $JobNm;
			// 	$data['GetJob'] = $this->db->select('HargaPerolehan,Estimasi')->where('JobNo', $JobNo)->get('Job')->row();
			// 	$this->load->view('form/updateappraisal/form_tambah', $data);
			// }

			if ($type == 'Edit') {
				$LedgerNo = $this->input->post('LedgerNo', TRUE);
				$data['JobNo'] = $JobNo;
				$data['data'] = $this->M_getAjax->getYAD($LedgerNo);
				$this->load->view('form/YAD/form_edit', $data);
			}
		}

		
	}

	// function GetFormYAD()
	// {
	// 	if (isset($_POST)){
	// 		$type = $this->input->post('type, TRUE);
	// 		$JobNo = $this->input->post('JobNo', TRUE);

	// 		if ($type == 'edit'){
	// 			$LedgerNo = $this->input->post('LedgerNo', TRUE);
	// 			$data['data'] = $this->M_getAjax->getYAD($LedgerNo);
	// 			$this->load->view('form/YAD/form_edit', $data);
	// 		}
	// 	}
	// }


	//END DOMAIN YAD 
}

