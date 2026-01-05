<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PercobaanSaya extends CI_Controller {

	var $Company;
    
    function __construct()
    {
        parent::__construct();
        is_login();

        $this->load->helper('bantu');
        $this->Company = $this->config->item('Company');
        
    }

    function index(){
    	$data['judul'] = 'Percobaan Saya';
        $data['bodyclass'] = 'sidebar-collapse skin-purple-light';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('PercobaanSaya_view', $data);
        $this->load->view('templates/footer');
    }

    function data_json(){
    	header('Content-Type: application/json');
        echo $this->panggil_json();
    }

    // function panggil_json(){
    // 	$this->load->library('Datatables');

    // 	$this->datatables->select('JobNo, JobNm, Lokasi ');
    // 	$this->datatables->from('Job');
    // 	return $this->datatables->generate();

    // }

}

/* End of file PercobaanSaya.php */
/* Location: ./application/controllers/PercobaanSaya.php */