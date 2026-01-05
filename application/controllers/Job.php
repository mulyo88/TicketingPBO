<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Job extends CI_Controller
{
	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');

		CekAkses_dan_field_pada_database();
		$this->load->model(array("M_job"));
		$this->load->model(array("M_Kontrak"));
		// $this->load->model("M_Yad"));

	}

	public function uploadImgConf($dir = null)
	{
		$configImg['upload_path'] = './assets/files/' . $dir;
		$configImg['allowed_types'] = 'gif|jpg|png|jpeg';
		$configImg['max_size']  = '3000';
		$configImg['overwrite']  = TRUE;
		$configImg['encrypt_name']  = TRUE;
		return $this->load->library('upload', $configImg);
	}
	public function uploadFileConf($dir = null, $specialCondition = false)
	{
		$configFile['upload_path'] = './assets/filejaminan/' . $dir;
		if ($specialCondition) {
			$configFile['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
		} else {
			$configFile['allowed_types'] = 'pdf';
		}
		$configFile['max_size']  = '10000';
		$configFile['overwrite']  = TRUE;
		$configFile['encrypt_name']  = TRUE;
		return $this->load->library('upload', $configFile);
	}
	public function removeDecimal($value)
	{
		$arr = explode('.', $value);
		$callback = 0;
		if (isset($arr[0])) {
			$callback = $arr[0];
		}
		return $callback;
	}

	public function index()
	{

		redirect('Joblist','refresh');
		exit;

		$data['judul'] = 'List Data Job';

		$ListSesi = $this->session->userdata('MIS_LOGGED_TOKEN');

		$json = json_decode($ListSesi, true);
		$DataSesiUser = $json['UserID'];
		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$DataSesiUser' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode("','", $explode) . "'";

		$data['Job'] = $this->db->query("SELECT * From Job Where Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan','Close') and JobNo IN($implode) Order by JobNo DESC")->result();

		// print_rr($data);
		// exit;

		// $data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' AND TipeJob='NonProject'  and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		//   $data['Job'] = $this->M_job->data_job()->result();
		// $data['judul'] = 'DASHBOARD';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/job", $data);
		$this->load->view('templates/footer');
	}

	public function sub_job($jobNo = null)
	{
		if ($jobNo=='' || $jobNo==null) {
			redirect('Welcome');
			die;
		}
		$where = array('JobNo' => $jobNo);
		$data['job'] = $this->M_job->edit_data($where, 'Job')->result();
		$data['judul'] = 'List Your Job';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$data['datajob'] = dapat_data_job($jobNo);


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('content/sub_job',$data);
		// $this->load->view('content/sub_job_new', $data);
		$this->load->view('templates/footer');
	}

	public function dataproyek($JobNo)
	{
		if($this->session->userdata('PecahToken')->Akses_DataProyek == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
			
		}

		$this->load->model('m_job');
		$dataproyek = $this->m_job->dataproyek($JobNo);
		$data['dataproyek'] = $dataproyek;
		$data['judul'] = 'Data Proyek';
		$data['bodyclass'] = 'sidebar-collapse skin-green-light';
		$data['data_logo'] = $this->db->query("SELECT * FROM tbl_logo ORDER BY LogoID DESC  ")->result();


		//tampil logo image
		$PATH_LogoCompany = NULL;

		if ($dataproyek->Logo_baru !='' || $dataproyek->Logo_baru !=NULL ) {

			if (!file_exists('assets/doc/'.$dataproyek->Logo_baru)) {
				$PATH_LogoCompany = 'assets/doc/no_image.png';
			}else{
				$PATH_LogoCompany = 'assets/doc/'.$dataproyek->Logo_baru;
			}	
		}else{
			$PATH_LogoCompany = 'assets/doc/no_image.png';
		}

		$data['foto_logo_company'] = base_url($PATH_LogoCompany);
		// print_rr($data);
		// die;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/dataproyek", $data);
		$this->load->view('templates/footer');
	}

	public function UpdateDataProyek()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);


		if($this->session->userdata('PecahToken')->Akses_DataProyek == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		// print_rr($_POST);
		// die;

		
		$JobNm                   = $this->input->post('JobNm');
		$Lokasi                  = $this->input->post('Lokasi');
		$StatusJob               = $this->input->post('StatusJob');
		$CompanyId               = $this->input->post('CompanyId');
		$Deskripsi               = $this->input->post('Deskripsi');
		$Instansi                = $this->input->post('Instansi');
		$Kategori                = $this->input->post('Kategori');
		$SistemKontrak				 = $this->input->post('SistemKontrak');
		$RekPengeluaranPusat     = $this->input->post('RekPengeluaranPusat');
		$BankPengeluaranPusat    = $this->input->post('BankPengeluaranPusat');
		$RekPengeluaranProyek    = $this->input->post('RekPengeluaranProyek');
		$BankPengeluaranProyek   = $this->input->post('BankPengeluaranProyek');


		$NamaFile_logo = NULL;
		$Logo_baru = NULL;

		if (!empty($_FILES['Logo']['name'])) {

			$this->load->library('upload');
            // $config['upload_path']          = 'assets/dokumen_pendukung_karyawan/';
			$config['upload_path']          =  './assets/doc';
			$config['allowed_types']        = '*';
			$config['max_size']             = 2048;

			$config['file_name'] = $JobNo.'-'.$JobNm.'-'.date('ymdHis').' Logo-Company';


			$this->upload->initialize($config);

			if ($this->upload->data('file_size') > 2048 ) {
				is_pesan('info','maximal file 2MB');
				redirect('Job/dataproyek/'.$JobNo);
				exit;
			}

			if (!$this->upload->do_upload('Logo')) {
				is_pesan('error','Terjadi Kesalahan');
				redirect('Job/dataproyek/'.$JobNo);
				exit;

			} else {
				$Logo_baru = $this->upload->data('file_name');
			}
		}

		$data = array(
			'JobNm'                         => $JobNm,
			'Lokasi'                        => $Lokasi,
			'StatusJob'                     => $StatusJob,
			'CompanyId'                     => $CompanyId,
			'Deskripsi'                     => $Deskripsi,
			'Instansi'                      => $Instansi,
			'Kategori'                      => $Kategori,
			'SistemKontrak'			  => $SistemKontrak,
			'RekPengeluaranPusat'           => $RekPengeluaranPusat,
			'BankPengeluaranPusat'          => $BankPengeluaranPusat,
			'RekPengeluaranProyek'          => $RekPengeluaranProyek,
			'BankPengeluaranProyek'         => $BankPengeluaranProyek,
			// 'Logo'							=> $Logo,
			'Logo_baru' => $Logo_baru
		);



		$where = array(
			'JobNo' => $JobNo
		);

		// echo 'aa';
		// die;

		$this->db->trans_start();

		if (!empty($_FILES['Logo']['name'])) {
			update_logo_company($JobNo);
		}else{
			unset($data['Logo_baru']);
		}

		$this->db->set($data)->where($where)->update('Job');

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			is_pesan('error','terjadi kesalahan');
			redirect('Job/dataproyek/'.$JobNo);
			exit;
		}else{
			is_pesan('success','berhasil');
			redirect('Job/dataproyek/'.$JobNo);
			exit;
		}

		// $this->M_job->UpdateDataProyek('Job', $data, $where);
		// redirect('Job/dataproyek/' . $JobNo);
	}

	public function datakontrak($JobNo)
	{
		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}



		$this->load->model('m_job');
		$datakontrak = $this->m_job->datakontrak($JobNo);
		$data['datakontrak'] = $datakontrak;
		// print_rr($data);
		// die;

		$addendum = $this->m_job->addendum($JobNo);
		$data['addendum'] = $addendum;


		$JaminanPelaksanaan = $this->m_job->JaminanPelaksanaan($JobNo);
		$data['JaminanPelaksanaan'] = $JaminanPelaksanaan;

		$JaminanUangMuka = $this->m_job->JaminanUangMuka($JobNo);
		$data['JaminanUangMuka'] = $JaminanUangMuka;

		$JaminanSisPel = $this->m_job->JaminanSisPel($JobNo);
		$data['JaminanSisPel'] = $JaminanSisPel;

		$JaminanPemeliharaan = $this->m_job->JaminanPemeliharaan($JobNo);
		$data['JaminanPemeliharaan'] = $JaminanPemeliharaan;

		$dataAddendum = $this->m_job->dataAddendum($JobNo);
		$data['dataAddendum'] = $dataAddendum;

		$checkProjectFieldTeam =  $this->m_job->checkProjectFieldTeam($JobNo);		
		$data['checkProjectFieldTeam'] = $checkProjectFieldTeam;

		$checkProjectPCTeam = $this->m_job->checkProjectPCTeam($JobNo);
		$data['checkProjectPCTeam'] = $checkProjectPCTeam;

		$checkPHO1 = $this->m_job->checkPHO1($JobNo);
		$data['checkPHO1'] = $checkPHO1;

		$checkPHO2 = $this->m_job->checkPHO2($JobNo);
		$data['checkPHO2'] = $checkPHO2;

		// $data['judul'] = 'Data Kontrak / Addendum';
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'DATA KONTRAK / ADDENDUM';
		$data['bodyclass'] = 'skin-blue';

		$query = $this->db->select('JobNm,Lokasi')->where('JobNo',$JobNo)->get('Job')->row();
		$JobNm = $query->JobNm;
		$Lokasi = $query->Lokasi;

		$data['js_saya'] = 'content/JobEntry/js_datakontrak';

		// $data['var_AddendumKe'] = $this->getAddendumke($JobNo);
		// echo $data['var_AddendumKe'];
		// die;

		// print_rr($dataAddendum);
		// die;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/datakontrak", $data);
		$this->load->view('templates/footer');
	}

	// function getAddendumke($JobNo){
	// 	$var_AddendumKe = 0;
	// 	$query = $this->db->query("SELECT TOP 1 * AddendumKe FROM JobH WHERE JobNo='$JobNo' ORDER BY AddendumKe DESC");
	// 	if($query->num_rows()>0){
	// 		$data = $query->row();
	// 		$var_AddendumKe = $data->AddendumKe;

	// 	}else{
	// 		$var_AddendumKe = 0;
	// 	}
		

	// 	return $var_AddendumKe;
	// }

	public function updateDataKontrak()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$HPS = $this->input->post('HPS');
		$NilaiKontrak = $this->input->post('NilaiKontrak');
		$PaguBrutoKontrak = $this->input->post('PaguBrutoKontrak');
		$nettoKontrak = $this->input->post('KontrakNetto');


		$NoKontrak			= $this->input->post('NoKontrak');
		$TglKontrak			= $this->input->post('TglKontrak');
		$MasaPelaksanaan 	= $this->input->post('MasaPelaksanaan');
		$MasaPemeliharaan	= $this->input->post('MasaPemeliharaan');
		$RemarkAddendum		= $this->input->post('KeteranganAdd');
		$PrdAwal			= $this->input->post('PrdAwal');
		$PrdAkhir			= $this->input->post('PrdAkhir');
		$PenawaranNetto		= $this->input->post('PenawaranNetto');
		$RingkasanPekerjaan	= $this->input->post('RingPek');
		$NamaPPK			= $this->input->post('NamaPPK');
		$AlamatPPK			= $this->input->post('AlamatPPK');
		


		$PaguBrutoKontrak = floatval(preg_replace('/[^\d.]/', '', $this->input->post('PaguBrutoKontrak',TRUE)));
		$PaguNettoKontrak = floatval(preg_replace('/[^\d.]/', '', $this->input->post('PaguNettoKontrak',TRUE)));
		// echo $PaguBrutoKontrak.' - '.$PaguNettoKontrak."<br />";
		// echo floatval(preg_replace('/[^\d.]/', '', $this->input->post('PaguBrutoKontrak',TRUE)));

		// print_rr($_POST);
		// die;

		$data = array(
			'HPS' => $HPS,
			'Bruto' => $NilaiKontrak,
			'PaguBruto' => $PaguBrutoKontrak,
			'Netto' => $nettoKontrak,
			
			'NoKontrak'			=> $NoKontrak,
			'Hari'	=> $MasaPelaksanaan,
			'Minggu'	=> $MasaPemeliharaan,
			'RemarkAddendum'	=> $RemarkAddendum,
			'TglKontrak'		=> $TglKontrak,
			'PrdAwal'			=> $PrdAwal,
			'PrdAkhir'			=> $PrdAkhir,
			'PenawaranNetto'	=> HapusFormatUang($PenawaranNetto),
			'RingkasanPekerjaan' => $RingkasanPekerjaan,
			'NamaPPK'			=> $NamaPPK,
			'AlamatPPK'			=> $AlamatPPK,
			'PaguBruto' => $PaguBrutoKontrak,
			'PaguNetto' => $PaguNettoKontrak,
		);
		$where = array(
			'JobNo'		=> $JobNo
		);
		
		$this->M_job->UpdateDataProyek('Job', $data, $where);
		redirect('Job/datakontrak/' . ($JobNo));
	}

	public function tambahAddendum()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
		}

		
		$NoKontrak = $this->input->post('NoKontrak', TRUE);
		$TahunAnggaran = $this->input->post('TahunAnggaran', TRUE);
		$HPS = HapusFormatUang($this->input->post('HPS', TRUE));
		$Bruto = HapusFormatUang($this->input->post('Bruto', TRUE));
		$AddendumKe = $this->input->post('AddendumKe', TRUE);
		$TglKontrak = $this->input->post('TglKontrak', TRUE);
		$Hari = $this->input->post('Hari', TRUE);
		$Minggu = $this->input->post('Minggu', TRUE);
		$RemarkAddendum = $this->input->post('RemarkAddendum', TRUE);
		$PrdAwal = $this->input->post('PrdAwal', TRUE);

		$PrdAkhir = $this->input->post('PrdAkhir', TRUE);
		$SumberDana = $this->input->post('SumberDana', TRUE);
		$PenawaranNetto = HapusFormatUang($this->input->post('PenawaranNetto', TRUE));
		$Netto = HapusFormatUang($this->input->post('KontrakNetto', TRUE));
		$RingkasanPekerjaan = $this->input->post('RingkasanPekerjaan', TRUE);
		$NamaPPK = $this->input->post('NamaPPK', TRUE);
		$AlamatPPK = $this->input->post('AlamatPPK', TRUE);

		// echo $this->input->post('HPS', TRUE)."<br>";
		// echo HapusFormatUang($this->input->post('HPS', TRUE));
		$PaguBruto = $HPS;
		$PaguNetto = $HPS;
		if (CariKataLoan($SumberDana) != 'LOAN') {
			$PaguNetto = $PaguNetto / dapatkan_PPN();
			$PaguNetto = $PaguNetto * dapatkan_PPH();
			$PaguNetto = $PaguNetto;
		} else {
			$PaguNetto = $PaguNetto;
		}

		if ($RemarkAddendum=='') {
			is_pesan('danger','Keterangan addendum belum diisi.');
			redirect('Job/datakontrak/'.$JobNo);
			die;
		}

		$this->db->trans_begin();

		try {
			
			$UserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
			$TimeEntry	= date("Y-m-d H:i:s");


			// $data_TmpAddendum = array(
			// 	'JobNo' 		=> $JobNo,
			// 	'Bruto' 		=> (float) $Bruto,
			// 	'Netto' 		=> (float) $Netto,
			// 	'NoKontrak' 	=> $NoKontrak,
			// 	'TahunAnggaran' => $TahunAnggaran,
			// 	'TglKontrak' 	=> $TglKontrak,
			// 	'AddendumKe' 	=> $AddendumKe,
			// 	'PrdAwal' 		=> $PrdAwal,
			// 	'PrdAkhir' 		=> $PrdAkhir,
			// 	'Hari' 			=> $Hari,
			// 	'RemarkAddendum' => $RemarkAddendum,
			// 	'UserEntry'		=> $UserEntry,
			// 	'TimeEntry' 	=> $TimeEntry,
			// 	'RingkasanPekerjaan' => $RingkasanPekerjaan,
			// 	'NamaPPK' => $NamaPPK,
			// 	'AlamatPPK' => $AlamatPPK,
			// 	'PaguBruto' => (float) $PaguBruto,
			// 	'PaguNetto' => (float) $PaguNetto
			// );

			// print_rr($data_TmpAddendum);
			// die;


			$data_SetJob = array(

				'NoKontrak' => $NoKontrak,
				'TahunAnggaran' => $TahunAnggaran,
				'HPS' => $HPS,
				'Bruto' => (float) $Bruto,
				'AddendumKe' => $AddendumKe,
				'TglKontrak' => $TglKontrak,
				'Hari' => $Hari,
				'Minggu' => $Minggu,
				'RemarkAddendum' => $RemarkAddendum,
				'PrdAwal' => $PrdAwal,
				'PrdAkhir' => $PrdAkhir,
				'SumberDana' => $SumberDana,
				'PenawaranNetto' => $PenawaranNetto,
				'Netto' => (float) $Netto,
				'RingkasanPekerjaan' => $RingkasanPekerjaan,
				'NamaPPK' => $NamaPPK,
				'AlamatPPK' => $AlamatPPK,
				'PaguBruto' => (float) $PaguBruto,
				'PaguNetto' => (float) $PaguNetto

			);

			$where_SetJob = array(
				'JobNo' => $JobNo,
			);

			// $tes = $this->db->query("SELECT JobNo, Bruto, Netto, PaguBruto, PaguNetto, NoKontrak, TglKontrak, AddendumKe, RemarkAddendum, PrdAwal, PrdAkhir, Hari, UserEntry, TimeEntry FROM Job WHERE JobNo='$JobNo'");
			// print_rr($tes->result());
			// die;

			$this->db->query("INSERT INTO JobH (JobNo,Bruto,Netto,PaguBruto,PaguNetto,NoKontrak,TglKontrak,AddendumKe,RemarkAddendum,PrdAwal,PrdAkhir,Hari,UserEntry,TimeEntry)
				SELECT JobNo, Bruto, Netto, PaguBruto, PaguNetto, NoKontrak, TglKontrak, AddendumKe, RemarkAddendum, PrdAwal, PrdAkhir, Hari, '$UserEntry', '$TimeEntry' FROM Job WHERE JobNo='$JobNo' ");
			
			$this->db->set($data_SetJob)->where($where_SetJob)->update('Job');
			// $this->db->insert('JobH',$data_TmpAddendum);
			


            // Jika semua operasi berhasil, commit transaksi
			$this->db->trans_commit();
			is_pesan('success', 'Updated!');
			redirect('Job/datakontrak/' . $JobNo);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			is_pesan('danger', 'Failed Updated!');
			redirect('Job/datakontrak/' . $JobNo);
		}
		// die;

		// print_rr($data);
		// die;

		// is_pesan('success', 'Berhasil mengubah dataa');

		// $simpan = $this->M_job->UpdateAddendum($data, $JobNo);
		// redirect('Job/datakontrak/' . $JobNo);
		// exit;


		// print_rr($data);
		// echo "<hr>";
		// print_rr($_POST);
		// exit;	




		// $JobNo = $this->input->post('JobNo', TRUE);
		// $NoKontrak = $this->input->post('NoKontrak', TRUE);
		// $TahunAnggaran = $this->input->post('TA', TRUE);
		// $HPS = $this->input->post('HPS', TRUE);
		// $NilaiKontrak = $this->input->post('NilaiKontrak', TRUE);
		// $KeteranganAdd = $this->input->post('KeteranganAdd', TRUE);
		// $TglAddendum1 = $this->input->post('TglAddendum1', TRUE);
		// $TglAddendum2 = $this->input->post('TglAddendum2', TRUE);
		// $Sumberdana = $this->input->post('Sumberdana', TRUE);
		// $PenawaranNetto = $this->input->post('PenawaranNetto', TRUE);
		// $KontrakNetto = $this->input->post('KontrakNetto', TRUE);
		// $RingPek = $this->input->post('RingPek', TRUE);
		// $NamaPPK = $this->input->post('NamaPPK', TRUE);
		// $AlamatPPK = $this->input->post('AlamatPPK', TRUE);


		// $fieldsData = $this->db->field_data("Job");
		// $datacc = array();
		// foreach ($fieldsData as $key => $field) {
		// 	$datacc[$field->name] = $this->input->post($field->name);
		// }

		// print_rr($datacc);

		// $fields = $this->db->field_data('Job');
		// $datacc = array();
		// foreach ($fields as $field)
		// {
		// 	$datacc[$field->name] = $this->input->post($field->name);



		// 	echo $field->name."<br>";
		// 	echo $field->type."<br>";
		// 	echo $field->max_length."<br>";
		// 	echo "<hr>";
		// }

		// $data = array(
		// 	'JobNo'			=> $JobNo,
		// 	'NoKontrak'			=> $NoKontrak,
		// 	'Hari'	=> $MasaPelaksanaan,
		// 	'Minggu'	=> $MasaPemeliharaan,
		// 	'RemarkAddendum'	=> $RemarkAddendum,
		// 	'PrdAwal'			=> $PrdAwal,
		// 	'PrdAkhir'			=> $PrdAkhir,
		// 	'PenawaranNetto'	=> $PenawaranNetto,
		// 	'RingkasanPekerjaan' => $RingkasanPekerjaan,	
		// 	'NamaPPK'			=> $NamaPPK,
		// 	'AlamatPPK'			=> $AlamatPPK,
		// );

		// $data = array(
		// 	'JobNo' => $JobNo,
		// 	'NoKontrak' => $NoKontrak,
		// 	'TahunAnggaran' => $TahunAnggaran,
		// 	'HPS' => $HPS,
		// 	'NoKontrak' => $NilaiKontrak,
		// 	'NoKontrak' => $KeteranganAdd,
		// 	'NoKontrak' => $TglAddendum1,
		// 	'NoKontrak' => $TglAddendum2,
		// 	'NoKontrak' => $Sumberdana,
		// 	'NoKontrak' => $PenawaranNetto,
		// 	'NoKontrak' => $KontrakNetto,
		// 	'NoKontrak' => $RingPek,
		// 	'NoKontrak' => $NamaPPK,
		// 	'NoKontrak' => $AlamatPPK,

		// );




		// $AddendumKe = $this->input->post('AddendumKe', TRUE);
		// $TglKontrak = $this->input->post('TglKontrak', TRUE);
		// $MasaPelaksanaan = $this->input->post('MasaPelaksanaan', TRUE);
		// $MasaPemeliharaan = $this->input->post('MasaPemeliharaan', TRUE);
		// $RemarkAddendum = $this->input->post('RemarkAddendum', TRUE);
		// $PrdAwal = $this->input->post('PrdAwal', TRUE);
		// $PrdAkhir = $this->input->post('PrdAkhir', TRUE);
		// $PenawaranNetto = $this->input->post('PenawaranNetto', TRUE);
		// $RingkasanPekerjaan = $this->input->post('RingkasanPekerjaan', TRUE);
		// $NamaPPK = $this->input->post('NamaPPK', TRUE);
		// $AlamatPPK = $this->input->post('AlamatPPK', TRUE);
		// $NilaiKontrak = $this->input->post('NilaiKontrak', TRUE);

		// $data = array(
		// 	'NoKontrak' => $NoKontrak,
		// 	'TahunAnggaran' => $TahunAnggaran,
		// 	'HPS' => $HPS,
		// 	'NilaiKontrak' => $NilaiKontrak,
		// 	'AddendumKe' => $AddendumKe,
		// 	'TglKontrak' => $TglKontrak,
		// 	'MasaPelaksanaan' => $MasaPelaksanaan,
		// 	'MasaPemeliharaan' => $MasaPemeliharaan,
		// 	'KeteranganAdd' => $KeteranganAdd,
		// 	'RemarkAddendum' => $RemarkAddendum,
		// 	'PrdAwal' => $PrdAwal,
		// 	'PrdAkhir' => $PrdAkhir,
		// 	'PenawaranNetto' => $PenawaranNetto,
		// 	'RingkasanPekerjaan' => $RingkasanPekerjaan,
		// 	'NamaPPK' => $NamaPPK,
		// 	'AlamatPPK' => $AlamatPPK


		// );

		// $data = array(
		// 	'NoKontrak'				=> $this->input->post('NoKontrak'),
		// 	'TahunAnggaran'		=> $this->input->post('TA'),
		// 	'HPS'						=> $this->input->post('HPS'),
		// 	'Bruto'					=> $this->input->post('NilaiKontrak'),
		// 	'AddendumKe'			=> $this->input->post('AddendumKe'),
		// 	'TglKontrak'			=> $this->input->post('TglKontrak'),
		// 	'MasaPelaksanaan' 	=> $this->input->post('MasaPelaksanaan'),
		// 	'MasaPemeliharaan'	=> $this->input->post('MasaPemeliharaan'),
		// 	'RemarkAddendum'		=> $this->input->post('KeteranganAdd'),
		// 	'PrdAwal'				=> $this->input->post('TglAddendum1'),
		// 	'PrdAkhir'				=> $this->input->post('TglAddendum2'),
		// 	'PenawaranNetto'		=> $this->input->post('PenawaranNetto'),
		// 	'RingkasanPekerjaan' => $this->input->post('RingPek'),
		// 	'NamaPPK'				=> $this->input->post('NamaPPK'),
		// 	'AlamatPPK'				=> $this->input->post('AlamatPPK'),
		// );



		// $this->M_job->SimpanData('Job', $data);
		// redirect('Job/datakontrak/');
	}

	function hapus_history_addendum($JobNo, $LedgerNo){
		$where = array('LedgerNo' => $LedgerNo);
		$this->M_job->hapus_data($where, 'JObH');
		redirect('Job/datakontrak/' . $JobNo);

		// if($JobNo ==''){
		// 	is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
		// 	redirect('welcome','refresh');
		// 	exit;
		// }

		// $where = array(
		// 	'JobNo' => $JobNo,
		// 	'AddendumKe' => $AddendumKe,
		// 	'LedgerNo' => $LedgerNo
		// );

		// $query = $this->db->where($where)->delete('JobH');

		// if ($this->db->affected_rows() > 0) {
		// 	is_pesan('success','Berhasil dihapus');
		// 	redirect('Job/datakontrak/'.$JobNo);
		// }else{
		// 	is_pesan('error','terjadi kesalahan');
		// 	redirect('Job/datakontrak/'.$JobNo);
		// }



	}

	public function editAddendum($LedgerNo='')
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		// print_rr($_POST);
		// die;


		$LedgerNo 			=$this->input->post('LedgerNo');
		$NoKontrak			= $this->input->post('NoKontrak');
		$PaguBruto					= $this->input->post('PaguBruto');
		$PaguNetto 			= $this->input->post('PaguNetto');
		$NilaiKontrak		= $this->input->post('Bruto');
		$AddendumKe			= $this->input->post('AddendumKe');
		$TglKontrak			= $this->input->post('TglKontrak');
		$MasaPelaksanaan  = $this->input->post('Hari');
		$RemarkAddendum		= $this->input->post('RemarkAddendum');
		$PrdAwal			= $this->input->post('PrdAwal');
		$PrdAkhir			= $this->input->post('PrdAkhir');
		$Netto		= $this->input->post('KontrakNetto');

		$PaguBruto = CekDanHapusKoma($PaguBruto);
		$PaguBruto = (float) $PaguBruto;

		$PaguNetto = CekDanHapusKoma($PaguNetto);
		$PaguNetto = (float) $PaguNetto;

		$NilaiKontrak = CekDanHapusKoma($NilaiKontrak);
		$NilaiKontrak = (float) $NilaiKontrak;

		$Netto = CekDanHapusKoma($Netto);
		$Netto = (float) $Netto;

		$data = array(
			'NoKontrak'		=>$NoKontrak,
			'PaguBruto'		=>$PaguBruto,
			'PaguNetto'		=>$PaguNetto,
			'Bruto'			=>$NilaiKontrak,
			'Netto' 			=> $Netto,
			'Hari'			=>$MasaPelaksanaan,
			'RemarkAddendum' =>$RemarkAddendum,
			'PrdAwal' 		=>$PrdAwal,
			'PrdAkhir'		=>$PrdAkhir,
			'Tglkontrak'	=>$TglKontrak,
			'AddendumKe' => $AddendumKe,
		);

		// print_rr($_POST);
		// echo '<hr>';
		// print_rr($data);
		// die;

		$where = array(
			'LedgerNo'		=> $LedgerNo,
		);


		$this->M_job->UpdateDataProyek('JobH', $data, $where);
		redirect('Job/datakontrak/' . ($JobNo));
	}


	public function checklistAction()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		
		$UserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry	= date("Y-m-d H:i:s");

		$data = array(
			'JobNo'		=> $JobNo,
			'CeklistLapangan'	=> implode(',', $this->input->post('Cekfield', TRUE)),
			'CeklistPC'	=> implode(',', $this->input->post('Cekpc', TRUE)),
			'UserEntry'	=> $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		// print_r($cekbox);

		$this->M_job->SimpanData('CeklistDoc', $data);
		redirect('Job/datakontrak/' . $JobNo);

		// foreach($this->input->post('Ceklist') as $row => $value) {
		// 	// print_r($value);
		// }
	}

	public function SimpanPHO($JobNo = null)
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		
		$NoPHO 		= $this->input->post('NoPHO');
		$TglPHO		= $this->input->post('TglPHO');

		$config['upload_path']   = './assets/FilePHO/';
		$config['allowed_types'] = 'pdf';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('FilePHO')) {
			print_r($this->upload->display_errors());
			// die;
		} else {
			$FilePHO = $this->upload->data('file_name');
		}

		// $this->upload->do_upload('FilePHO');
		// $FilePHO = $this->upload->data('file_name');

		$data = array(
			'NoPHO' 		=> $NoPHO,
			'TglPHO'		=> $TglPHO,
			'PHOFile'		=> $FilePHO,
			'ChkDokPHO1'	=> implode(',', $this->input->post('CeklistPHO1', TRUE)),
			'ChkDokPHO2'	=> implode(',', $this->input->post('CeklistPHO2', TRUE)),
		);
		$where = array(
			'JobNo' => $JobNo
		);
		// print_r($data);
		// die();

		$this->M_job->UpdateDataProyek('Job', $data, $where);
		redirect('Job/datakontrak/' . ($JobNo));
	}

	public function UpdateFHO()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$NoFHO      = $this->input->post('NoFHO');
		$TglFHO     = $this->input->post('TglFHO');
		$EndNotification = $this->input->post('NotifLanjutTender');
		$config['upload_path']   = './assets/FileFHO/';
		$config['allowed_types'] = 'pdf';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('FileFHO')) {
			print_r($this->upload->display_errors());
			die;
		} else {
			$FileFHO = $this->upload->data('file_name');
		}

		$data = array(
			'NoFHO'     => $NoFHO,
			'TglFHO'    => $TglFHO,
			'FHOFile'	=> $FileFHO,
			'EndNotification' => $EndNotification,
		);
		$where = array(
			'JobNo' => $JobNo
		);

		$this->M_job->UpdateDataProyek('Job', $data, $where);
		redirect('Job/datakontrak/' . ($JobNo));
	}


	public function TambahRekNPWP()
	{

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}


		$JobNm         	= $this->input->post('JobNm');
		$NPWPName       = $this->input->post('NPWPName');
		$NPWPCompany    = $this->input->post('NPWPCompany');
		$RekeningProyek = $this->input->post('RekeningProyek');
		$NPWPAddress    = $this->input->post('NPWPAddress');
		$BankProyek     = $this->input->post('BankProyek');

		$data = array(
			'JobNm'				=> $JobNm,
			'NPWPName'			=> $NPWPName,
			'NPWPCompany'		=> $NPWPCompany,
			'RekeningProyek'	=> $RekeningProyek,
			'NPWPAddress'		=> $NPWPAddress,
			'BankProyek'        => $BankProyek,
		);
		$where = array(
			'JobNo' => $JobNo
		);

		$this->M_job->updateDRP('Job', $data, $where);
		redirect('job/datakontrak/' . ($JobNo));
	}

	public function JaminanKontrak($JobNo, $NamaJaminan)
	{

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
		}


		$this->load->model('m_job');
		$JaminanKontrak = $this->m_job->JaminanKontrak($JobNo, $NamaJaminan);
		$data['JaminanKontrak'] = $JaminanKontrak;
	}

	public function JaminanPelaksanaan($JobNo, $NamaJaminan)
	{
		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
		}

		$this->load->model('m_job');
		$JaminanPelaksanaan = $this->m_job->JaminanPelaksanaan($JobNo, $NamaJaminan);
		$data['JaminanPelaksanaan'] = $JaminanPelaksanaan;
	}

	public function JaminanUangMuka($JobNo, $NamaJaminan)
	{
		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
		}

		$this->load->model('m_job');
		$JaminanUangMuka = $this->m_job->JaminanUangMuka($JobNo, $NamaJaminan);
		$data['JaminanUangMuka'] = $JaminanUangMuka;
	}

	public function JaminanSisPel($JobNo, $NamaJaminan)
	{
		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
		}

		$this->load->model('M_job');
		$JaminanSisPel = $this->m_job->JaminanSisPel($JobNo, $NamaJaminan);
		$data['JaminanSisPel'] = $JaminanSisPel;
	}

	public function JaminanPemeliharaan($JobNo, $NamaJaminan)
	{
		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
		}

		$this->load->model('M_job');
		$JaminanPemeliharaan = $this->m_job->JaminanPemeliharaan($JobNo, $NamaJaminan);
		$data['JaminanPemeliharaan'] = $JaminanPemeliharaan;
	}

	public function simpan_JaminanKontrak()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_DataKontrak == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}
		
		$NamaJaminan        = $this->input->post('NamaJaminan');
		$NoJaminan          = $this->input->post('NoJaminan');
		$NilaiJaminan       = $this->input->post('NilaiJaminan');
		$MasaBerlaku        = $this->input->post('MasaBerlaku');
		$Filejaminan        = $this->input->post('FileJaminan');
		$UserEntry          = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry           = date("Y-m-d H:i:s");

		$Filejaminan        = $_FILES['Filejaminan']['name'];
		if ($Filejaminan = '') {
		} else {
			$config['upload_path']          = './assets/filejaminan';
			$config['allowed_types']        = 'pdf';
			$config['max_size']             = 2000;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('Filejaminan')) {
				print_r($this->upload->display_errors());
				die;
			} else {
				$Filejaminan = $this->upload->data('file_name');
			}
		}

		$data = array(
			'JobNo'         => $JobNo,
			'NamaJaminan'   => $NamaJaminan,
			'NoJaminan'     => $NoJaminan,
			'NilaiJaminan'  => $NilaiJaminan,
			'MasaBerlaku'   => $MasaBerlaku,
			'UserEntry'     => $UserEntry,
			'TimeEntry'     => $TimeEntry,
			'Filejaminan'   => $Filejaminan,
		);

		$this->M_job->simpan_JaminanKontrak('JaminanKontrak', $data);
		redirect('Job/datakontrak/' . ($JobNo));
	}

	private function GetJob_For_Dipa(){

		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE (StatusJob='Pelaksanaan' OR StatusJob='Pemeliharaan') AND TipeJob='Project' AND Company='$this->Company'");

		$ArrayJob = array();
		foreach($QueryJob->result_array() as $myjob ):

			$ArrayJob[] = (object) array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;
	}

	public function dipa($JobNo='')
	{

		if($this->session->userdata('PecahToken')->RencanaTermin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		if (!$this->db->field_exists('NilaiRetensi', 'RencanaTermin'))
		{
			$this->db->query("ALTER TABLE RencanaTermin ADD NilaiRetensi money");
		}
		if (!$this->db->field_exists('TotalPotongan', 'RencanaTermin'))
		{
			$this->db->query("ALTER TABLE RencanaTermin ADD TotalPotongan money");
		}

		if ($this->input->post('JobNo')) {
			$JobNo = $this->input->post('JobNo');
			redirect('Job/dipa/'.$JobNo,'refresh');
			die;
		}

		$this->load->model('m_job');
		$datadipa = $this->m_job->dipa($JobNo);
		$data['dipa'] = $datadipa;

		$tbldipa = $this->m_job->tbldipa($JobNo);
		$data['tbldipa'] = $tbldipa;

		$TblRencanaTermin = $this->m_job->getRtermin($JobNo);
		$data['tblRtermin'] = $TblRencanaTermin;

		// $TtlDipa = $this->m_job->SumDipaBruto($JobNo);
		// $data['ttlDipa'] = $TtlDipa;
		$TtlDipa = $this->m_job->SumBrutoJob($JobNo);
		$data['ttlBrutoJob'] = $TtlDipa;

		$TtlDipa = $this->m_job->SumDipaPaguBudget($JobNo);
		$data['ttlPaguBudget'] = $TtlDipa;

		$getBrutoTermin = $this->m_job->getBrutoTermin($JobNo);
		$data['getBrutoTermin'] = $getBrutoTermin;

		$getBruto = $this->m_job->GetBruto($JobNo);
		$data['GetBruto'] = $getBruto;

		$data['SumRTBruto'] = $this->M_job->SumRTBruto($JobNo);
		$data['SumRTNetto'] = $this->M_job->SumRTNetto($JobNo);

		$dataBruto = $this->M_job->data_job($JobNo);
		$data['Bruto'] = $dataBruto;

		$tblTerminInduk = $this->M_job->getTblTerminInduk($JobNo);
		$data['TblTerminInduk'] = $tblTerminInduk;

		$TblTerminMember = $this->M_job->getTblTerminMember($JobNo);
		$data['TblTerminMember'] = $TblTerminMember;

		$data['judul'] = 'DIPA';
		$data['bodyclass'] = 'sidebar-collapse skin-yellow-light';

		$query = $this->db->select('JobNm,Lokasi')->where('JobNo',$JobNo)->get('Job')->row();
		$JobNm = $query->JobNm;
		$Lokasi = $query->Lokasi;


		$cek_dipa_total = $this->db->query("SELECT SUM(PaguBudget) AS 'TotalPaguBudget_DIPA' FROM Dipa WHERE JobNo='$JobNo' GROUP BY JobNo  ")->row_array();
		$data['TotalPaguBudget_DIPA'] = $cek_dipa_total['TotalPaguBudget_DIPA'];

		$data['PaguBruto_dipa'] = $datadipa->PaguBruto;

		$data['JobNo'] = $JobNo;

		$data['ListJob'] = $this->GetJob_For_Dipa();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/dipa", $data);
		$this->load->view('templates/footer');
	}

	public function TambahDipa()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$Tahun      = $this->input->post('Tahun');
		$Budget     = str_replace(',', '', $this->input->post('Dipa'));
		$PaguBruto  = str_replace(',', '', $this->input->post('PaguBudget'));
		$UserEntry  = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  = date("Y-m-d H:i:s");

		$data = array(
			'JobNo'     => $JobNo,
			'Tahun'     => $Tahun,
			'Budget'    => $Budget,
			'PaguBudget' => $PaguBruto,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
		);
		$this->M_job->TambahDipa('DIPA', $data);
		redirect('Job/dipa/' . ($JobNo));
	}

	public function editDipa()
	{

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$id_Dipa 	= $this->input->post('id_Dipa');
		$JobNo		= $this->input->post('JobNo');
		$Dipa 		= str_replace(',', '', $this->input->post('Dipa'));
		$Tahun		= $this->input->post('Tahun');
		$PaguBudget	= str_replace(',', '', $this->input->post('PaguBudget'));

		$data = array(
			'JobNo'			=> $JobNo,
			'Budget'		=> $Dipa,
			'Tahun'			=> $Tahun,
			'PaguBudget'	=> $PaguBudget,
		);
		$where = array(
			'id_Dipa'	=> $id_Dipa,
		);
		$this->M_job->UpdateDataProyek('DIPA', $data, $where);
		redirect('Job/dipa/' . ($JobNo));
	}

	function DeleteDipa($JobNo = null, $id_Dipa = null)
	{
		// $this->m_job->DeleteDipa($id_Dipa);
		if($this->session->userdata('PecahToken')->Termin == 0){
			redirect('Welcome','refresh');
		}
		$where = array('id_Dipa' => $id_Dipa);
		$this->M_job->DeleteDipa($where, 'DIPA');
		redirect('job/dipa/' . ($JobNo));
	}

	function addDPtermin()
	{

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}


		$Jenis			= $this->input->post('JenisTermin');
		$TglRencana		= $this->input->post('tglRencanaTermin');
		$Uraian			= $this->input->post('UraianTermin');
		$Persentase		= $this->input->post('TxtPersentase');
		$Bruto			= $this->input->post('TxtA');
		$PresentaseUM	= $this->input->post('TxtD');
		$Netto 			= $this->input->post('TxtK');
		//$Retensi		= $this->input->post('cbRetensi');
		$UserEntry		= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$data = array(
			'JobNo'			=> $JobNo,
			'Jenis'			=> $Jenis,
			'TglRencana'	=> $TglRencana,
			'Uraian'		   => $Uraian,
			'Persentase'	=> $Persentase,
			'Bruto'			=> str_replace(',', '', $Bruto),
			'PersentaseUM'	=> str_replace(',', '', $PresentaseUM),
			'Netto'			=> str_replace(',', '', $Netto),
			'Retensi'		=> implode(',', $this->input->post('cbRetensi[]', TRUE)),
			'UserEntry'		=> $UserEntry,
			'TimeEntry'		=> $TimeEntry,
		);

		$this->M_job->SimpanData('RencanaTermin', $data);
		redirect('Job/dipa/' . $JobNo);
	}

	function TambahTermin()
	{

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo1', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}
		
		$Jenis			= $this->input->post('JenisTermin1');
		$TglRencana		= $this->input->post('tglRencanaTermin1');
		$Uraian			= $this->input->post('UraianTermin1');
		$Persentase		= $this->input->post('TxtPersentase1');
		$Bruto			= $this->input->post('TxtA1');
		$PresentaseUM	= $this->input->post('TxtD1');
		$NilaiPotUM		= $this->input->post('TxtUM1');
		$NilaiRetensi	= $this->input->post('TxtE1');
		$TotalPotongan	= $this->input->post('TxtF1');
		$PembayaranFisik = $this->input->post('TxtG1');
		$PPN 			= $this->input->post('TxtH1');
		$NetExcPPN		= $this->input->post('TxtI1');
		$PPH 			= $this->input->post('TxtJ1');
		$Netto 			= $this->input->post('TxtK1');
		//$Retensi		= $this->input->post('cbRetensi');
		$UserEntry		= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$Retensi = ($this->input->post('cbRetensi', TRUE)) ? 1 : 0;


		$data = array(
			'JobNo' => $JobNo,
			'Jenis' => $Jenis,
			'TglRencana' => $TglRencana,
			'Uraian' => $Uraian,
			'Persentase' => $Persentase,
			'Bruto' => str_replace(',','',$Bruto),
			// 'BuktiRealisasiLalu' => $JobNo,
			'PersentaseUM' => $PresentaseUM,
			// 'Retensi' => $Retensi,
			'Netto' => str_replace(',','',$NetExcPPN),
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'NilaiPotUM' => str_replace(',','',$PresentaseUM),
			'NilaiRetensi'	=> str_replace(',', '', $NilaiRetensi),
			'TotalPotongan'	=> str_replace(',', '', $TotalPotongan),
		);

		// print_rr($_POST);
		// echo "<hr>";
		// print_rr($data);
		// echo $Retensi;
		// exit;
		

		$this->M_job->SimpanData('RencanaTermin', $data);
		// die;
		
		redirect('Job/dipa/' . $JobNo);
	}

	function DelRTermin($id = null, $JobNo = null)
	{
		
		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$where = array('LedgerNo' => $id);
		$this->M_job->hapus_data($where, 'RencanaTermin');
		redirect('Job/dipa/' . $JobNo);
	}

	public function SimpanTerminInduk()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}
		
		$Jenis			= $this->input->post('JenisTermin');
		$TglCair			= $this->input->post('TglCair');
		$NoBAP 			= $this->input->post('NoBap');
		$Uraian			= $this->input->post('Uraian');
		$BrutoBOQ		= $this->input->post('BrutoBoQ');
		$UM				= $this->input->post('PotUM');
		$Retensi			= $this->input->post('Potretensi');
		$TerminInduk	= $this->input->post('NettoBoQ');
		$UserEntry		= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$data = array(
			'JobNo'			=> $JobNo,
			'Jenis'			=> $Jenis,
			'TglCair'		=> $TglCair,
			'NoBAP' 			=> $NoBAP,
			'Uraian'			=> $Uraian,
			'BrutoBOQ'		=> $BrutoBOQ,
			'UM'				=> $UM,
			'Retensi'		=> $Retensi,
			'TerminInduk'	=> $TerminInduk,
			'UserEntry'		=> $UserEntry,
			'TimeEntry'		=> $TimeEntry,
		);

		$this->M_job->SimpanData('TerminInduk', $data);
		Redirect('Job/dipa/' . $JobNo);
	}

	public function EditTerminInduk()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$LedgerNo		= $this->input->post('LedgerNo');
		$Jenis			= $this->input->post('JenisTermin');
		$TglCair			= $this->input->post('TglCair');
		$NoBAP 			= $this->input->post('NoBap');
		$Uraian			= $this->input->post('Uraian');
		$BrutoBOQ		= $this->input->post('BrutoBoQ');
		$UM				= $this->input->post('PotUM');
		$Retensi			= $this->input->post('Potretensi');
		$TerminInduk	= $this->input->post('NettoBoQ');

		$data = array(
			'JobNo'			=> $JobNo,
			'Jenis'			=> $Jenis,
			'TglCair'		=> $TglCair,
			'NoBAP' 		=> $NoBAP,
			'Uraian'		=> $Uraian,
			'BrutoBOQ'		=> $BrutoBOQ,
			'UM'			=> $UM,
			'Retensi'		=> $Retensi,
			'TerminInduk'	=> $TerminInduk,
		);

		$where = array(
			'LedgerNo' => $LedgerNo,
		);
		$this->M_job->UpdateDataProyek('TerminInduk', $data, $where);
		Redirect('Job/dipa/' . $JobNo);
	}

	function DelTerminInduk($id = null, $JobNo = null)
	{

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$where = array('LedgerNo' => $id);
		$this->M_job->hapus_data($where, 'TerminInduk');
		redirect('Job/dipa/' . $JobNo);
	}



	public function SimpanTerminMember()
	{

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}
		

		$Jenis				= $this->input->post('JenisTermin');
		$TglCair 			= $this->input->post('TglCair');
		$TglSetor			= $this->input->post('TglSetor');
		$NoBAP				= $this->input->post('NoBap');
		$Uraian				= $this->input->post('Uraian');
		$NettoLeader		= $this->input->post('NettoLeader');
		$NettoMember		= $this->input->post('NettoMember');
		$CadanganMember1	= $this->input->post('CadanganMember1');
		$CadanganMember2	= $this->input->post('CadanganMember2');
		$UserEntry			= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  		= date("Y-m-d H:i:s");

		$data =  array(
			'JobNo'					=> $JobNo,
			'Jenis'					=> $Jenis,
			'TglCair'				=> $TglCair,
			'TglSetor'				=> $TglSetor,
			'NoBAP'					=> $NoBAP,
			'Uraian'					=> $Uraian,
			'TerminMember1'		=> $NettoLeader,
			'TerminMember2'		=> $NettoMember,
			'CadanganKSO'			=> $CadanganMember1,
			'CadanganKSOMember1'	=> $CadanganMember2,
			'UserEntry'				=> $UserEntry,
			'TimeEntry'				=> $TimeEntry,
		);
		$this->M_job->SimpanData('TerminMember', $data);
		Redirect('Job/dipa/' . $JobNo);
	}

	public function EditTerminMember()
	{
		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$LedgerNo		= $this->input->post('LedgerNo');
		// $JobNo  			= $this->input->post('JobNo');
		$Jenis				= $this->input->post('JenisTermin');
		$TglCair 			= $this->input->post('TglCair');
		$TglSetor			= $this->input->post('TglSetor');
		$NoBAP				= $this->input->post('NoBap');
		$Uraian				= $this->input->post('Uraian');
		$NettoLeader		= $this->input->post('NettoLeader');
		$NettoMember		= $this->input->post('NettoMember');
		$CadanganMember1	= $this->input->post('CadanganMember1');
		$CadanganMember2	= $this->input->post('CadanganMember2');
		$UserEntry			= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  		= date("Y-m-d H:i:s");

		$data =  array(
			'JobNo'					=> $JobNo,
			'Jenis'					=> $Jenis,
			'TglCair'				=> $TglCair,
			'TglSetor'				=> $TglSetor,
			'NoBAP'					=> $NoBAP,
			'Uraian'					=> $Uraian,
			'TerminMember1'		=> $NettoLeader,
			'TerminMember2'		=> $NettoMember,
			'CadanganKSO'			=> $CadanganMember1,
			'CadanganKSOMember1'	=> $CadanganMember2,
			'UserEntry'				=> $UserEntry,
			'TimeEntry'				=> $TimeEntry,
		);

		$where = array(
			'LedgerNo' => $LedgerNo,
		);
		$this->M_job->UpdateDataProyek('TerminMember', $data, $where);
		Redirect('Job/dipa/' . $JobNo);
	}

	public function tatakelola($JobNo)
	{
		redirect('Master/TataKelola_new/data_list_tatakelola/'.$JobNo,'refresh');
		exit;

		$this->load->model('m_job');
		$tatakelola = $this->M_job->tatakelola($JobNo);
		$data['tatakelola'] = $tatakelola;

		$getTataKelola = $this->M_job->GetTataKelola($JobNo);
		$data['GetTataKelola'] = $getTataKelola;

		$GetMpp = $this->M_job->GetMpp($JobNo);
		$data['TblMPP'] = $GetMpp;

		$data['judul'] = 'TaTa Kelola';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/tatakelola", $data);
		$this->load->view('templates/footer');
	}

	public function updateDATAKSO()
	{
		$JobNo 				= $this->input->post('JobNo');
		$Kso					= $this->input->post('Kso');
		$TipeManagerial	= $this->input->post('TipeManagerial');
		$NoRekKSO			= $this->input->post('NoRekKSO');
		$Member1				= $this->input->post('NamaLeader');
		$PersenShare1		= $this->input->post('PersenLeader');
		$BrutoShare1 		= $this->input->post('BrutoLeader');
		$BankInduk			= $this->input->post('BankLeader');
		$NoRekInduk			= $this->input->post('NoRekLeader');
		$Member2 			= $this->input->post('NamaMember');
		$PersenShare2 		= $this->input->post('PersenMember');
		$BrutoShare2 		= $this->input->post('BrutoMember');
		$BankMember 		= $this->input->post('BankMember');
		$NoRekMember 		= $this->input->post('NoRekMember');

		$data = array(
			'KSO'     			=> $Kso,
			'TipeManajerial' 	=> $TipeManagerial,
			'NoRekKSO'			=> $NoRekKSO,
			'Member1'			=> $Member1,
			'PersenShare1'		=> $PersenShare1,
			'BrutoShare1'		=> $BrutoShare1,
			'BankInduk'			=> $BankInduk,
			'NoRekInduk'		=> $NoRekInduk,
			'Member2'			=> $Member2,
			'PersenShare2' 	=> $PersenShare2,
			'BrutoShare2' 		=> $BrutoShare2,
			'BankMember'		=> $BankMember,
			'NoRekMember'		=> $NoRekMember,
		);
		$where = array(
			'JobNo'  => $JobNo,
		);
		$this->M_job->UpdateDataProyek('Job', $data, $where);
		Redirect('Job/tatakelola/' . $JobNo);
	}


	public function rap($JobNo)
	{
		$this->load->model('m_job');
		$rap = $this->m_job->rap($JobNo);
		$data['rap'] = $rap;

		$AksesAlokasi = $this->m_job->GetAlokasi();
		$data['AksesAlokasi'] = $AksesAlokasi;

		$data['judul'] = 'RAP';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/rap", $data);
		$this->load->view('templates/footer');
	}

	public function pdpj($JobNo)
	{
		$this->load->model('m_job');
		$pdpj = $this->m_job->pdpj($JobNo);
		$data['pdpj'] = $pdpj;

		$data['judul'] = 'PD & PJ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/pdpj", $data);
		$this->load->view('templates/footer');
	}

	public function rppm($JobNo)
	{
		$this->load->model('m_job');
		$this->load->model('m_rppm');

		$rppm = $this->m_job->rppm($JobNo);
		$data['rppm'] = $rppm;

		$TblProgress = $this->m_rppm->tblProgress($JobNo);
		$data['Progress'] = $TblProgress;
		// $data['listrppm'] = $this->M_job->getlist_rppm($config["per_page"], $data['page'])->result();

		$data['judul'] = 'RPPM';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('content/jobEntry/rppm', $data);
		$this->load->view('templates/footer');
	}

	public function spr($JobNo)
	{

		redirect('FrmSPR/view/'.$JobNo,'refresh');

		$this->load->model('m_job');
		$spr = $this->m_job->spr($JobNo);
		$data['spr'] = $spr;

		$data['judul'] = 'Daftar Surat Permintaan Material / Alat';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/spr", $data);
		$this->load->view('templates/footer');
	}

	public function Leaflet($JobNo)
	{
		$this->load->model('m_job');
		$leaflet = $this->m_job->leaflet($JobNo);
		$data['leaflet'] = $leaflet;

		$data['judul'] = 'Input leaflet Proyek';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/leaflet", $data);
		$this->load->view('templates/footer');
	}

	public function ProgressFisik($JobNo)
	{
		$this->load->model('m_job');
		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['ProgressFisik'] = $ProgressFisik;

		$data['judul'] = 'PROGRESS FISIK';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/progressfisik", $data);
		$this->load->view('templates/footer');
	}

	function EntryProgressFisik($JobNo)
	{
		$JobNo		= $this->input->post('JobNo');
		$Tahun		= $this->input->post('Tahun');
		$bulan 		= $this->input->post('Bulan');
		$RencanaTB	= $this->input->post('RencanaFisTB');
		$RealisasiTB	= $this->input->post('RealisasiFisTB');
		$RealisasiKeuTB	= $this->input->post('RealisasiKeuTB');
		$RencanaFisKom		= $this->input->post('RencanaFiskom');
		$RealisasiFisKom	= $this->input->post('RealisasiFiskom');
		$realisasiKeuKom	= $this->input->post('realisasiKeuKom');
	}

	function ajax()
	{
		if (!$this->input->post()) {
			die;
		}

		$KdRAP = $this->input->post('KdRAP', TRUE);

		// $query = $this->db->query("SELECT TOP 1 ISNULL(Uraian,NULL) as Uraian, 
		// 	ISNULL(Alokasi,NULL) as Alokasi, 
		// 	ISNULL(Uom,NULL) as Uom,
		// 	ISNULL(Vol,0) as Vol, ISNULL(HrgSatuan,0) as HrgSatuan, ISNULL(PPN_New_Nominal,0) as PPN_New_Nominal, ISNULL(Vol*HrgSatuan,0) AS TtlPPN
		// 	FROM RAP WHERE KdRAP='$KdRAP' ");
		$query = $this->db->query("SELECT TOP 1 * FROM RAP WHERE KdRAP='$KdRAP' ");
		$ArrayBuatan = array();
		if ($query->num_rows() > 0) {
			// foreach($query->result() as $row):
			$row = $query->row();
			$Vol = CekNULL_KhususAngka($row->Vol);
			$HrgSatuan = CekNULL_KhususAngka($row->HrgSatuan);
			$PPN_New_Nominal = CekNULL_KhususAngka($row->PPN_New_Nominal);

			$Amount = $Vol*$HrgSatuan;
			$TtlPPN = $Vol*$PPN_New_Nominal;

			$ArrayBuatan =  array(
				'Uraian' => CekNULL_KhususString($row->Uraian),
				'Alokasi' => CekNULL_KhususString($row->Alokasi),
				'Uom' => CekNULL_KhususString($row->Uom),
				'Vol' => UbahDecimal_Nol_tiga($Vol),
				'HrgSatuan' => UbahDecimal_Nol_Dua($HrgSatuan),
				'PPN' => UbahDecimal_Nol_Dua($PPN_New_Nominal),
				'TtlPPN' => UbahDecimal_Nol_Dua($TtlPPN),
				'Amount' => UbahDecimal_Nol_Dua($Amount),
			);
			// endforeach;
		}

		echo json_encode($ArrayBuatan);
		// die;

		// echo json_encode($query);

		// print_rr();
		// exit;

	}

	public function YAD($JobNo)
	{
		$this->load->model('m_job');
		$this->load->model('M_Yad');

		$GetYAD = $this->m_job->mos($JobNo);
		$data['GetYAD'] = $GetYAD;

		$TblYAD = $this->m_job->TblYAD($JobNo);
		$data['TblYAD'] = $TblYAD;

		$AksesJob = $this->m_job->GetJob_By_User();
		$data['AksesJob'] = $AksesJob;

		$GetAlokasi = $this->m_job->GetAlokasi();
		$data['GetAlokasi'] = $GetAlokasi;

		$GetRap = $this->M_Yad->getRAP($JobNo)->result_array();
		$data['GetRap'] = $GetRap;

		$getterpakai = $this->M_Yad->terpakai($JobNo)->result_array();
		$data['Terpakai'] = $getterpakai;

		$TotalKO = $this->M_Yad->TotalKO($JobNo)->result_array();
		$data['TotalKO'] = $TotalKO;

		$TotalInv = $this->M_Yad->TotalInv($JobNo)->result_array();
		$data['TotalInv'] = $TotalInv;

		$getYad = $this->M_Yad->YAD($JobNo)->result_array();
		$data['getYAD'] = $getYad;

		$GetKdRAP = $this->db->query("SELECT * FROM RAP WHERE (Alokasi='B' OR Alokasi='C') AND Tipe ='Detail' AND JobNo='$JobNo'")->result();
		$data['KdRAP'] = $GetKdRAP;

		$getSumHrgSatuan = $this->db->query("SELECT SUM(HrgSatuan) As TtlSatuan FROM YAD WHERE JobNo = $JobNo")->row();
		$data['TtlSatuan'] = $getSumHrgSatuan;

		$getSumHrg = $this->db->query("SELECT SUM(Amount) As TtlHrg FROM YAD WHERE JobNo = $JobNo")->row();
		$data['TtlHrg'] = $getSumHrg;

		$getSumPPNSatuan = $this->db->query("SELECT SUM(PPN) As TtlPPNSat FROM YAD WHERE JobNo = $JobNo")->row();
		$data['TtlPPNSat'] = $getSumPPNSatuan;

		$getSumPPN = $this->db->query("SELECT SUM(TtlPPN) As TtlPPN FROM YAD WHERE JobNo = $JobNo")->row();
		$data['TtlPPN'] = $getSumPPN;


		$q_grfik_YAD= $this->db->query("select 
			isnull ((select (SUM(vol * HrgSatuan)) from RAP where Alokasi IN('B','C') AND Tipe !='Header' AND JobNo='$JobNo'),0) as TTLRAP,
			isnull ((select (SUM(SubTotal-DiscAmount+PPN)) FROM KoHdr where ApprovedBy is not null and CanceledBy is NUll AND JobNo='$JobNo'),0) AS TOTALKO,
			isnull ((SELECT (SUM(Total)) FROM Invoice WHERE JobNo='$JobNo'), 0)AS TOTALINV,
			isnull ((select (SUM(SubTotal-DiscAmount+PPN)) FROM KoHdr where ApprovedBy is not null and CanceledBy is NUll AND JobNo='$JobNo') - ((SELECT (SUM(Total)) FROM Invoice WHERE JobNo='$JobNo')),0) AS WIP,
			isnull ((select (SUM(Amount))  from YAD Where JobNo='$JobNo'),0) as getYAD")->row_array();
		$data['q_yad'] = $q_grfik_YAD; 
		


		// print_rr($data['GetRap']);
		// exit;

		



		$data['judul'] = 'ENTRY YAD';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/EntryYAD", $data);
		$this->load->view('templates/footer');
	}

	function getTipeForm()
	{
		$this->load->model('m_job');
		$Alokasi = $this->input->post('Alokasi');
		$getTipeForm = $this->m_job->getTipeForm($Alokasi);
		foreach ($getTipeForm as $tf) {
			echo '<option value="' . $tf->TipeForm . '">' . $tf->TipeForm . ' - ' . $tf->Keterangan . ' </option>';
			# code...
		}
		// print_r($getTipeForm);
	}

	function EntryYAD()
	{

		// $TipeForm 	= $this->input->post('TipeForm');
		// $TglYAD 		= $this->input->post('TglYAD');
		// $NilaiYAD   = $this->input->post('NilaiYAD');

		$JobNo 		= $this->input->post('JobNo');
		$Tgl 		= $this->input->post('TglYAD');
		$KdRAP    	= $this->input->post('KdRAP');
		$Uraian	 	= $this->input->post('Uraian');
		$Alokasi 		= $this->input->post('Alokasi');
		$Satuan  		= $this->input->post('Satuan');
		$Vol     		= $this->input->post('Vol');
		$HrgSatuan 	= $this->input->post('HrgSatuanYAD');
		$Amount   	= $this->input->post('Amount');
		$PPN      	= $this->input->post('PPN');
		$TtlPPN   	= $this->input->post('TtlPPN');	
		$Remark 		= $this->input->post('Remark');
		$UserEntry	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$Vol = TemukanKomaDanHapus($Vol);
		$HrgSatuan = TemukanKomaDanHapus($HrgSatuan);
		$Amount = TemukanKomaDanHapus($Amount);
		$TtlPPN = TemukanKomaDanHapus($TtlPPN);
		// die;
		$data = array(
			'JobNo'		=> $JobNo,
			'Tgl'		=> $Tgl,
			'KdRAP'		=> $KdRAP,
			'Uraian'		=> $Uraian,
			'Alokasi'		=> $Alokasi,
			'Uom'		=> $Satuan,
			'Vol'		=> $Vol,
			'HrgSatuan'	=> $HrgSatuan,
			'Amount'		=> $Amount,
			'PPN'		=> $PPN,
			'TtlPPN'		=> $TtlPPN,
			'Remark'		=> $Remark,
			'UserEntry'	=> $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		// print_rr($_POST);
		// echo '<hr>';
		// print_rr($data);
		// die;

		$this->M_job->SimpanData('YAD', $data);
		// Redirect('Job/YAD/' . $JobNo);

		if ($this->db->affected_rows() > 0) {
			is_pesan('success','data telah ditambahkan');
			redirect('Job/YAD/'.$JobNo,'refresh');
		}else{
			is_pesan('error','data gagal ditambahkan');
			redirect('Job/YAD/'.$JobNo,'refresh');
		}
	}

	function EditYAD($LedgerNo='')
	{
		if ($LedgerNo=='') {
			echo 'error';
			die;
		}

		$JobNo 		= $this->input->post('JobNo');
		$Tgl 		= $this->input->post('TglYAD');
		$KdRAP    	= $this->input->post('KdRAP');
		$Uraian	 	= $this->input->post('Uraian');
		$Alokasi 		= $this->input->post('Alokasi');
		$Satuan  		= $this->input->post('Satuan');
		$Vol     		= $this->input->post('Vol');
		$HrgSatuan 	= $this->input->post('HrgSatuanYAD');
		$Amount   	= $this->input->post('Amount');
		$PPN      	= $this->input->post('PPN');
		$TtlPPN   	= $this->input->post('TtlPPN');	
		$Remark 		= $this->input->post('Remark');
		$UserEntry	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$Vol = TemukanKomaDanHapus($Vol);
		$HrgSatuan = TemukanKomaDanHapus($HrgSatuan);
		$Amount = TemukanKomaDanHapus($Amount);
		$TtlPPN = TemukanKomaDanHapus($TtlPPN);

		$data = array(
			'Uom' => $Satuan,
			'Vol' => $Vol,
			'HrgSatuan' => $HrgSatuan,
			'PPN' => $PPN,
			'TtlPPN' => $TtlPPN,
			'Tgl'			=> $Tgl,
			'Amount'		=> $Amount,
			'Remark'		=> $Remark,
			'UserEntry'	=> $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		$where = array(
			'LedgerNo' => $LedgerNo,
		);

		// print_rr($_POST);
		// echo '<hr>';
		// print_rr($data);
		// die;

		$this->M_job->UpdateDataProyek('YAD', $data, $where);
		// Redirect('Job/YAD/' . $JobNo);
		if ($this->db->affected_rows() > 0) {
			is_pesan('success','data telah di-ubah');
			redirect('Job/YAD/'.$JobNo,'refresh');
		}else{
			is_pesan('error','data gagal di-ubah');
			redirect('Job/YAD/'.$JobNo,'refresh');
		}	

		// INI DATA YANG LAMA
		// $data = array(
		// 	'JobNo'		=> $JobNo,
		// 	'Alokasi'	=> $Alokasi,
		// 	'TipeForm'	=> $TipeForm,
		// 	'Tgl'			=> $TglYAD,
		// 	'Amount'		=> $NilaiYAD,
		// 	'Remark'		=> $RemarkYAD,
		// 	'UserEntry'	=> $UserEntry,
		// 	'TimeEntry' => $TimeEntry,
		// );

		// print_rr($_POST);
		// echo '<hr>';
		// print_rr($data);
		// die;


	}

	public function formKO($JobNo)
	{
		$this->load->model('m_job');
		$this->load->model('M_Kontrak');

		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['FormKO'] = $ProgressFisik;

		$Ko = $this->M_Kontrak->get_ko($JobNo);
		$data['KO'] = $Ko;


		$data['judul'] = 'DAFTAR KONTRAK / PURCHASE ORDER';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/FormKO", $data);
		$this->load->view('templates/footer');
	}

	public function FrmSelectKO($NoKO = null, $JobNo = null)
	{
		$this->load->model('m_job');

		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['FormKO'] = $ProgressFisik;

		$where = array('NoKO' => $NoKO);
		$data['view_datako'] = $this->db->query("SELECT A.*, B.*, C.JobNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId JOIN Job C ON A.JobNo=C.JobNo where A.NoKO='$NoKO'")->result();
		$data['v_KoDtl'] = $this->db->query("SELECT * FROM KoDtl Where NoKO='$NoKO'")->result();
		$data['checkSP'] = $this->m_job->checkSP($NoKO);
		$data['judul'] = "FORM SELECT KO";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('form/ko/SelectFormKO', $data);
		$this->load->view('templates/footer');
	}

	function FormEntryKO($JobNo = '')
	{
		$this->load->model('M_Kontrak');
		$this->load->model('m_job');

		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['FormKO'] = $ProgressFisik;

		$FormKO = $this->M_Kontrak->get_ko($JobNo);
		$data['KO'] = $FormKO;

		$data['judul'] = 'SUMMARY KONTRAK';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("form/FormEntryKO", $data);
		$this->load->view('templates/footer');
	}

	function FormEntryPO($JobNo = '')
	{
		$this->load->model('M_Kontrak');
		$this->load->model('m_job');

		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['FormKO'] = $ProgressFisik;

		$Ko = $this->M_Kontrak->get_ko($JobNo);
		$data['KO'] = $Ko;

		$data['judul'] = 'PURCHASING ORDER';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("form/FormEntryPO", $data);
		$this->load->view('templates/footer');
	}

	function FormEntryKomix($JobNo = '')
	{
		$this->load->model('M_Kontrak');
		$this->load->model('m_job');

		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['FormKO'] = $ProgressFisik;

		$Ko = $this->M_Kontrak->get_ko($JobNo);
		$data['KO'] = $Ko;

		$data['judul'] = 'PURCHASING ORDER';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("form/FormEntryPO", $data);
		$this->load->view('templates/footer');
	}

	function ApproveKO($JobNo = '')
	{
		$this->load->model('m_job');
		$this->load->model('m_Kontrak');

		$waitingAppKO = $this->m_Kontrak->approveKO();
		$data['WapproveKO'] = $waitingAppKO;

		$approvedKO = $this->m_Kontrak->approveKOyes();
		$data['approveKOyes'] = $approvedKO;

		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['jb'] = $ProgressFisik;

		$data['judul'] = 'APPROVAL KONTRAK/PURCHASE ORDER';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/approvedKO", $data);
		$this->load->view('templates/footer');
	}

	public function update_data($NoKO)
	{
		$this->load->model('m_job');

		$where = array('NoKO' => $NoKO);
		$data['view_datako'] = $this->db->query("SELECT A.*, B.*, C.JobNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId JOIN Job C ON A.JobNo=C.JobNo where A.NoKO='$NoKO'")->result();
		$data['v_KoDtl'] = $this->db->query("SELECT * FROM KoDtl Where NoKO='$NoKO'")->result();
		$data['checkSP'] = $this->m_job->checkSP($NoKO);
		$data['judul'] = "View Data KO";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('form/FormApprv_LihatKO', $data);
		$this->load->view('templates/footer');
	}

	public function update_data1($NoKO)
	{
		$this->load->model('m_job');

		$where = array('NoKO' => $NoKO);
		$data['view_datako'] = $this->db->query("SELECT A.*, B.*, C.JobNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId JOIN Job C ON A.JobNo=C.JobNo where A.NoKO='$NoKO'")->result();
		$data['v_KoDtl'] = $this->db->query("SELECT * FROM KoDtl Where NoKO='$NoKO'")->result();
		$data['checkSP'] = $this->m_job->checkSP($NoKO);
		$data['judul'] = "View Data KO";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('form/ko/Frm_detail_addendum_V', $data);
		$this->load->view('templates/footer');
	}

	public function ViewDetailPembatalanKO($NoKO)
	{
		$this->load->model('m_job');

		$where = array('NoKO' => $NoKO);
		$data['view_datako'] = $this->db->query("SELECT A.*, B.*, C.JobNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId JOIN Job C ON A.JobNo=C.JobNo where A.NoKO='$NoKO'")->result();
		$data['v_KoDtl'] = $this->db->query("SELECT * FROM KoDtl Where NoKO='$NoKO'")->result();
		$data['checkSP'] = $this->m_job->checkSP($NoKO);
		$data['judul'] = "View Data KO";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('form/ko/Frm_ViewDetailPembatalanKO', $data);
		$this->load->view('templates/footer');
	}

	function KOAddendum($JobNo = '', $NoKO = '')
	{
		$this->load->model('m_job');
		$this->load->model('m_kontrak');

		$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
		$data['jb'] = $ProgressFisik;

		$ListKO = $this->m_kontrak->tblAddmKO($JobNo);
		$data['ListKO'] = $ListKO;

		$where = array('NoKO' => $NoKO);
		$data['ListAddmKO'] = $this->db->query("SELECT A.*, B.VendorNm FROM KoHdrH A inner join Vendor B On A.VendorId = B.VendorId Where A.NoKO='$NoKO'  AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO")->result();
		// $ListAddmKO = $this->m_kontrak->ListAddmKO($NoKO);
		// $data['ListAddmKO'] = $ListAddmKO;

		$data['judul'] = 'ADDENDUM KONTRAK/PURCHASE ORDER';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/KOAddendum", $data);
		$this->load->view('templates/footer');
	}

	function Vendor()
	{
		$this->load->model('M_Job');

		$Vendor = $this->M_Job->getVendor()->result();
		$data['Vendor'] = $Vendor;

		$KodeVendor = $this->M_Job->kodevendor();
		$data['VendorId'] = $KodeVendor;

		$Bank = $this->db->query("SELECT * FROM Bank")->result();
		$data['Bank'] = $Bank;

		$data['judul'] = 'LIST VENDOR';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/vendor", $data);
		$this->load->view('templates/footer');
	}

	function EntryVendor()
	{
		$lastVendorID = $this->db->query("SELECT MAX(VendorId) as VendorId From Vendor");
		$NomorId = substr($lastVendorID, 3, 4);
		$VendorId = $NomorId + 1;
		$data = array('VendorId' => $VendorId);
	}

	public function mos($JobNo)
	{
		$this->load->model('m_job');
		$this->load->model('m_mos');

		$mos = $this->m_job->mos($JobNo);
		$data['mos'] = $mos;

		$GetNoMOS = $this->m_mos->NoMOS($JobNo)->row_array();
		$data['NoMOS'] = $GetNoMOS['NoSJ'];

		// print_rr($data);
		// exit;

		$TblMos = $this->m_mos->gettblmos($JobNo);
		$data['TblMos'] = $TblMos;

		$NoKO = $this->m_mos->NoKO($JobNo);
		$data['NoKO'] = $NoKO;



		$data['judul'] = 'Data MOS ( Material On Site )';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/mos", $data);
		$this->load->view('templates/footer');
	}

	function TambahDataMOS()
	{
		$this->load->model('m_job');
		$this->load->model('M_Mos');

		$JobNo = $this->input->post('JobNo');
		$NoMOS = $this->input->post('NoMos');
		$NoSJVendor = $this->input->post('NoSjVendor');
		$TglSJ = $this->input->post('tglMos');

		$data = array(
			'JobNo' => $JobNo,
			'NoSJ' => $NoMOS,
			'NoSJVendor' => $NoSJVendor,
			'TglSJ'  => $TglSJ
		);

		// $where = array(
		// 	'JobNo' => $JobNo
		// );

		$this->M_Mos->simpan_MOSIN('SjHdr', $data);
		redirect('job/mos/' . ($JobNo));
	}

	public function ApproveMos($JobNo)
	{
		$this->load->model('m_job');
		$this->load->model('m_mos');

		$mos = $this->m_job->mos($JobNo);
		$data['mos'] = $mos;

		$GetNoMOS = $this->m_mos->NoMOS($JobNo)->row_array();
		$data['NoMOS'] = $GetNoMOS['NoSJ'];

		// print_rr($data);
		// exit;

		$TblMos = $this->m_mos->gettblmos($JobNo);
		$data['TblMos'] = $TblMos;

		$NoKO = $this->m_mos->NoKO($JobNo);
		$data['NoKO'] = $NoKO;



		$data['judul'] = 'Data MOS ( Material On Site )';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/ApproveMos", $data);
		$this->load->view('templates/footer');
	}

	public function mos_Barang_Masuk($JobNo=null)
	{
		$this->load->model('m_job');
		$this->load->model('m_mos');

		$mos = $this->m_job->mos($JobNo);
		$data['mosin'] = $mos;

		$GetNoMOS = $this->m_mos->NoMOS($JobNo)->row_array();
		$data['NoMOS'] = $GetNoMOS['NoSJ'];

		// print_rr($data);
		// exit;

		$TblMos = $this->m_mos->gettblmos($JobNo);
		$data['TblMos'] = $TblMos;

		$NoKO = $this->m_mos->NoKO($JobNo);
		$data['NoKO'] = $NoKO;



		$data['judul'] = 'Data MOS ( Material On Site )';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/mos_Barang_Masuk", $data);
		$this->load->view('templates/footer');
	}


	public function StockKeluar($JobNo)
	{
		$this->load->model('m_job');
		$this->load->model('m_mos');

		$mos = $this->m_job->mos($JobNo);
		$data['mosout'] = $mos;

		$DataMos = $this->m_mos->getDataMos($JobNo);
		$data['selectMos'] = $DataMos;

		$NoKO = $this->m_mos->NoKO($JobNo);
		$data['NoKO'] = $NoKO;


		$data['judul'] = 'Data MOS ( Material On Site )';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobentry/barangKeluar", $data);
		$this->load->view('templates/footer');
	}
	
	function ApproveSpr($JobNo)
	{
		$this->load->model('m_job');

		$Appspr = $this->m_job->ProgressFisik($JobNo);
		$data['appspr'] = $Appspr;

		$GetSpr = $this->db->query("SELECT * FROM PrHdr Where JobNo = '$JobNo'")->result();
		$data['GetSpr'] = $GetSpr;

		$data['judul'] = 'APPROVAL SURAT PERMINTAAN MATERIAL/ALAT';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobEntry/ApproveSpr", $data);
		$this->load->view('templates/footer');
	}

	function editapprvspr($NoSPR)
	{
		$where = array('NoSPR' => $NoSPR);
		$data['detailspr'] = $this->db->query("SELECT * FROM PRHdr WHERE NoSPR='$NoSPR'")->row();

		$data['PRDtl'] = $this->db->query("SELECT * FROM PRDtl WHERE NoSPR='$NoSPR'")->result();

		$data['judul'] = 'Edit/Review Surat Permintaan Material/Alat';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("spr/form-edit-approved", $data);
		$this->load->view('templates/footer');
	}

	function pembatalanko($JobNo)
	{
		$this->load->model('m_job');

		$Appspr = $this->m_job->ProgressFisik($JobNo);
		$data['Pembatalanko'] = $Appspr;;

		$data['QueryKO'] = $this->db->query("SELECT A.*, B.VendorNm, (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoKO=A.NoKO) AS PaymentAmount FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId WHere A.JobNo='$JobNo' ORDER BY NoKO DESC ")->result();

		$data['judul'] = 'PEMBATALAN KO';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobEntry/pembatalanko", $data);
		$this->load->view('templates/footer');
	}

	function invoice($JobNo)
	{

		redirect('Entry/Procurement/Invoice/index/'.$JobNo,'refresh');
		exit;
		$this->load->model('m_job');
		$this->load->model('Invoice_model');

		$TblModel = $this->Invoice_model->get_Inv_table($JobNo);
		$data['tblinvoice'] = $TblModel;

		$Appspr = $this->m_job->ProgressFisik($JobNo);
		$data['invoice'] = $Appspr;
		
		$NoKO = $this->Invoice_model->NoKO($JobNo);
		$data['NoKO'] = $NoKO;

		$data['judul'] = 'MENU INVOICE';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/jobEntry/invoice", $data);
		$this->load->view('templates/footer');
	}

	// function BatalKO($JobNo = '')
	// {
	// 	$this->load->model('m_job');
	// 	$this->load->model('m_Kontrak');

	// 	$waitingAppKO = $this->m_Kontrak->approveKO();
	// 	$data['WapproveKO'] = $waitingAppKO;

	// 	$approvedKO = $this->m_Kontrak->approveKOyes();
	// 	$data['approveKOyes'] = $approvedKO;

	// 	$ProgressFisik = $this->m_job->ProgressFisik($JobNo);
	// 	$data['jb'] = $ProgressFisik;

	// 	$data['judul'] = 'APPROVAL KONTRAK/PURCHASE ORDER';
	// 	$this->load->view('templates/header', $data);
	// 	$this->load->view('templates/sidebar');
	// 	$this->load->view("form/ko/frmdetailbatalKO", $data);
	// 	$this->load->view('templates/footer');
	// }

	function tbl_detail_ko()
	{
		if (!$this->input->post()) {
			exit;
		}

		$NoKO = $this->input->post('NoKO', TRUE);
		$JobNo = $this->input->post('JobNo', TRUE);
		$data['NoKO'] = $NoKO;


		$query = $this->dapatkan_detail_ko_untuk_tambah_mos($data);
		$data['JobNo'] = $JobNo;
		$data['mydata'] = $query;

		$this->load->view('content/JobEntry/tbl_detail_ko', $data);
	}

	private function dapatkan_detail_ko_untuk_tambah_mos($data)
	{
		$this->db->select('*,(Vol * HrgSatuan) as total ');
		$this->db->where($data);
		return $this->db->get('KoDtl');
	}



	// == DITAMBAHKAN PADA TGL 05 OKTOBER 2023 =
	function HapusYAD($LedgerNo='',$JobNo=''){
		if ($LedgerNo=='') {
			echo 'error';
			die;
		}

		$query = $this->db->query("DELETE FROM YAD WHERE LedgerNo='$LedgerNo' ");
		if ($this->db->affected_rows() > 0) {
			is_pesan('success','data telah dihapus');
			redirect('Job/YAD/'.$JobNo,'refresh');
		}else{
			is_pesan('error','data gagal dihapus');
			redirect('Job/YAD/'.$JobNo,'refresh');
		}

		// $query = $this->db->query("SELECT * FROM YAD WHERE LedgerNo='$LedgerNo' ")->num_rows();
		// if ($query->num_rows() >0) {

		// 	$this->

		// }else{
		// 	echo 'data tidak ditemukan';
		// 	die;
		// }

	}
}
