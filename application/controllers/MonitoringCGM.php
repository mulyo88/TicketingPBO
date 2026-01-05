
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MonitoringCGM extends CI_Controller
{

	public function index()
	{
		$this->load->model('M_MonitoringCGM');

		$Query = $this->M_MonitoringCGM->GetTbl1();
		$data['queryCGM'] = $Query;

		
		$data['judul'] = 'Monitoring Cek/Giro Mundur';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/FrmMonitoringCGM", $data);
		$this->load->view('templates/footer');
	}
}
