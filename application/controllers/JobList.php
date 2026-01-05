<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JobList extends CI_Controller {

	var $Company;

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model(array("M_job"));
	}

	function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'LIST DATA JOB';
		$data['bodyclass'] = 'sidebar-collapse skin-green-light fixed';

		$data['listArea'] = $this->db->query("Select * From Area")->result();

		// $data['list_job'] = dapat_list_job();

		// print_rr(dapat_list_job());
		// exit;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('job_list_view', $data);
		$this->load->view('templates/footer');
	}

	public function TambahJoblist()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}

		$KdJob = $this->input->post('KdJob');
		$JobName = $this->input->post('Area');
		$Deskripsi = $this->input->post('Keterangan');

		$data = array(
			'JobNo' =>$KdJob,
			'JobNm' =>$JobName,
			'Deskripsi' =>$Deskripsi,
			'TipeJob'  =>'NonProject',
			'StatusJob' =>'Pelaksanaan',
			'Company'  => 'EPROC'
		);

		// print_rr($data);
		// exit;

		$this->M_job->SimpanData('Job', $data);
		// redirect('JobList/Index/');


	}

	function get_job_json(){
		if (!$this->input->post()) {
			exit;
		}

		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		$Company = $this->config->item('Company');

		header('Content-Type: application/json');

		$this->load->library('Datatables');

		$this->db->query("
			DROP TABLE IF EXISTS #temp_tbl_job
			CREATE TABLE #temp_tbl_job(
			JobNo nvarchar(10) NOT NULL,
			JobNm nvarchar(100) NOT NULL,
			StatusJob nvarchar(20) NULL,
			Deskripsi nvarchar(4000) NULL,
			CompanyId nvarchar(100) NULL,
			Kategori nvarchar(10) NULL,
			
			)

			INSERT INTO #temp_tbl_job (JobNo, JobNm, StatusJob, Deskripsi, CompanyId, Kategori)
			SELECT ax.JobNo, bx.JobNm, bx.StatusJob, bx.Deskripsi, bx.CompanyId, bx.Kategori FROM
			(SELECT item AS JobNo
			FROM
			dbo.SplitString ((SELECT TOP 1 a.AksesJob FROM (SELECT * FROM Login) AS a
			LEFT OUTER JOIN
			(SELECT * FROM Job) AS b
			on b.JobNo = a.AksesJob
			WHERE a.UserID= '$UserID'), ',')) AS ax
			LEFT OUTER JOIN 
			Job AS bx
			ON bx.JobNo = ax.JobNo WHERE bx.Company='$Company' AND (bx.StatusJob='Pelaksanaan' OR bx.StatusJob='Pemeliharaan' )
			GROUP BY ax.JobNo, bx.JobNm, bx.StatusJob, bx.Deskripsi, bx.CompanyId, bx.Kategori


			");

		$this->datatables->select('JobNo, JobNm, StatusJob, Deskripsi, CompanyId, Kategori');
		$this->datatables->from('#temp_tbl_job');
		$this->datatables->add_column('select_job','<a href="'.site_url('Job/sub_job/$1').'" title="SELECT Job" class="btn btn-link btn-sm" ><u>SELECT</u></a>','JobNo ');
		echo $this->datatables->generate();
	}

}

/* End of file JobList.php */
/* Location: ./application/controllers/JobList.php */