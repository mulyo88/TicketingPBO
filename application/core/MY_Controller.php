<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $parseData = [
		'content' => 'content/dashboard',
		'title' => 'AIS',
	];
	public $provinces = [
		"Aceh",
		"Sumatera Utara",
		"Sumatera Barat",
		"Riau",
		"Kepulauan Riau",
		"Jambi",
		"Sumatera Selatan",
		"Kepulauan Bangka Belitung",
		"Bengkulu",
		"Lampung",
		"DKI Jakarta",
		"Banten",
		"Jawa Barat",
		"Jawa Tengah",
		"DI Yogyakarta",
		"Jawa Timur",
		"Bali",
		"Nusa Tenggara Barat",
		"Nusa Tenggara Timur",
		"Kalimantan Barat",
		"Kalimantan Tengah",
		"Provinsi Kalimantan Selatan",
		"Kalimantan Timur",
		"Kalimantan Utara",
		"Sulawesi Utara",
		"Gorontalo",
		"Sulawesi Tengah",
		"Sulawesi Barat",
		"Provinsi Sulawesi Selatan",
		"Sulawesi Tenggara",
		"Maluku",
		"Maluku Utara",
		"Papua Barat",
		"Papua",
	];
	public $pesertaTender = [
		"PT. MINARTA DUTAHUTAMA",
		"PT. KARYA PRIMA MANDIRI PRATAMA",
		"PT. KARYA DULUR SAROHA",
		"PT. BAHANA KRIDA NUSANTARA",
		"PT. BAWAKARAENG PURNAMA WIJAYA",
		"PT. RELIS SAPINDO UTAMA",
		"PT. ADHI KARYA",
		"PT. WASKITA KARYA",
		"PT. WIJAYA KARYA",
		"PT. BRANTAS ABIPRAYA",
		"PT. PEMBANGUNAN PERUMAHAN",
		"PT. NINDYA KARYA",
		"PT. HUTAMA KARYA",
		"KELMAN INFRA PRATAMA",
		"PT. WIJAYA KARYA SEMESTA",
		"PT. MARI BANGUN NUSANTARA",
		"PT. JAYA TEKNIK LESTARI",
		"PT. SUMBER KARSA INDAH UTAMA",
		"PT. PARTO ADI NUGROHO",
		"PT. ANINDYA PUTRI PERTIWI",
		"PT. TIRSA ARTA MANDIRI",
		"CV. CAHAYA BRINGIN",
	];
	public $posisi = [
		'Komisaris',
		'Direktur Utama',
		'Direktur Keuangan',
		'Direktur Operasi',
		'GM Keuangan',
		'GM SDM',
		'GM Operasi',
		'GM Pemasaran',
		'Manager Akuntansi',
		'Manager Keuangan',
		'Manager IT',
		'Manager Legal',
		'Manager SDM',
		'Manager Umum',
		'Manager Pemasaran',
		'Manager Procurement',
		'Manager Project',
		'Koordinator Proyek',
		'Staff Akuntansi',
		'Staff Keuangan',
		'Kasir',
		'Staff IT',
		'Staff Legal',
		'Staff SDM',
		'Staff Umum',
		'Supir',
		'Staff Pemasaran',
		'Staff TEE',
		'Staff Procurement',
		'Staff Project',
		'Kepala Proyek',
		'Pelaksana K3',
		'QA/QC',
		'Kepala Pelaksana',
		'Kepala Komersial',
		'Kepala Urusan',
		'Kepala Enjinering',
		'Pelaksana Sipil',
		'Pelaksana ME',
		'Pelaksana Arsitek',
		'Surveyor',
		'Admin Teknik',
		'Logistik',
		'Admin Keuangan',
		'Staff Gudang',
		'Admin Umum',
		'Drafter',
		'Enjiner ME',
		'Enjiner Sipil'
	];

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		if (!$this->session->userdata('MIS_LOGGED_TOKEN')) {
			redirect('Auth');
		}
		$this->load->model('Instansi','instansi',TRUE);
		$this->load->model('TahapanTender','tahapanTender',TRUE);
		$this->load->model('InfoPasar','infoPasar',TRUE);
		$this->load->model('Karyawan','karyawan',TRUE);
		$this->load->model('Job','job',TRUE);
		$this->load->model('Users','users',TRUE);
		$this->load->model('GlobalModel','global',TRUE);
		ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
		ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
		ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv
	}
	public function dateToPeriode($date) {
		$month = substr($date, 5,2);
		$year = substr($date, 0,4);
		return $this->genMonth($month).'-'.$year;

	}
	public function beautyDate($date,$withTime = null) {
		$response = substr($date, 8,2).' '.$this->genMonth(substr($date, 5,2)).' '.substr($date, 0,4);
		if ($withTime) {
			$response .= ' '.substr($date, 11,5);
		}
		return $response;
	}
	public function genMonth($value) {
		$callback = 'January';
		switch ($value) {
			case '01':
				$callback = 'January';
				break;
			case '02':
				$callback = 'February';
				break;
			case '03':
				$callback = 'Maret';
				break;
			case '04':
				$callback = 'April';
				break;
			case '05':
				$callback = 'Mei';
				break;
			case '06':
				$callback = 'Juni';
				break;
			case '07':
				$callback = 'July';
				break;
			case '08':
				$callback = 'Agustus';
				break;
			case '09':
				$callback = 'September';
				break;
			case '10':
				$callback = 'Oktober';
				break;
			case '11':
				$callback = 'November';
				break;
			case '12':
				$callback = 'Desember';
				break;
		}
		return $callback;
	}

	public function uploadImgConf($dir = null) {
		$configImg['upload_path'] = './assets/files/'.$dir;
		$configImg['allowed_types'] = 'gif|jpg|png|jpeg';
		$configImg['max_size']  = '3000';
		$configImg['overwrite']  = TRUE;
		$configImg['encrypt_name']  = TRUE;
		return $this->load->library('upload', $configImg);
	}
	public function uploadFileConf($dir = null, $specialCondition = false) {
		$configFile['upload_path'] = './assets/files/'.$dir;
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
	public function removeDecimal($value) {
		$arr = explode('.', $value);
		$callback = 0;
		if (isset($arr[0])) {
			$callback = $arr[0];
		}
		return $callback;
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */