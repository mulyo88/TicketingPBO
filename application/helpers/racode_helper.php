<?php
function JobBerdasarkanAksesJob_danDataJob()
{
	$CI = &get_instance();

	if ($CI->session->userdata('MIS_LOGGED_ID') == '') {
		exit;
	}

	$UserID = $CI->session->userdata('MIS_LOGGED_ID');
	$Company = $CI->config->item('Company');

	$AksesJob = $CI->db->query("SELECT AksesJob From Login WHERE UserID='$UserID'  ")->result();
	$PanggilJob = $CI->db->query("SELECT JobNo FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND (StatusJob='Pelaksanaan' OR StatusJob='Pemeliharaan') AND Company='$Company'")->result();

	$AksesJob_Array = array();
	foreach ($AksesJob as $AksesJob_data) {
		$AksesJob_Array[] = $AksesJob_data->AksesJob;
	}

	$JobArray = array();
	foreach ($PanggilJob as $PanggilJob_Data) {
		$JobArray[] = $PanggilJob_Data->JobNo;
	}

	$result = array_diff($AksesJob_Array, $JobArray);
	return $result;
}
function KodeUrutPd($JobNo = '', $Alokasi = '', $PD_Dari = '')
{
	$CI = &get_instance();
	// $eksekusi = $CI->db->select('JobNo,Alokasi,CounterPD')
	$eksekusi = $CI->db->select('*')
	->where('JobNo', $JobNo)
	->where('Alokasi', $Alokasi)
	->get('Counter');

	$kode_statik = '';
	$NamaCounter = '';
	if ($PD_Dari == 'RKD') {

		$kode_statik = 'PDRKD';
		$NamaCounter = 'CounterKSO';
	} else if ($PD_Dari == 'MIX') {

		$kode_statik = 'PDMIX';
		$NamaCounter = 'CounterPDMIX';
	} else {

		$kode_statik = 'PD';
		$NamaCounter = 'CounterPD';
	}


	$gabung = $kode_statik . $JobNo . $Alokasi;


	if ($eksekusi->num_rows() > 0) {
		$row = $eksekusi->row();
		$angka_counter_pd = $row->$NamaCounter + 1;
		$hitungan = sprintf('%05s', $angka_counter_pd);
		$gabungan = $gabung . $hitungan;
		return array(
			'KodeUrutPd' => $gabungan,
			'status_kode' => 'lama'
		);
	} else {
		$hitungan = '00001';
		$gabungan = $gabung . $hitungan;
		return array(
			'KodeUrutPd' => $gabungan,
			'status_kode' => 'baru'
		);
	}
}

function NoPD_untuk_PDRKD($JobNo = '', $Alokasi = '')
{
	$CI = &get_instance();
	$eksekusi = $CI->db->select('JobNo,Alokasi,CounterKSO')
	->where('JobNo', $JobNo)
	->where('Alokasi', $Alokasi)
	->get('Counter');
	$kode_statik = "PDRKD";
	$gabung = $kode_statik . $JobNo . $Alokasi;


	if ($eksekusi->num_rows() > 0) {
		$row = $eksekusi->row();
		$angka_counter_pd = $row->CounterKSO + 1;
		$hitungan = sprintf('%05s', $angka_counter_pd);
		$gabungan = $gabung . $hitungan;
		return array(
			'KodeUrutPd' => $gabungan,
			'status_kode' => 'lama'
		);
	} else {
		$hitungan = '00001';
		$gabungan = $gabung . $hitungan;
		return array(
			'KodeUrutPd' => $gabungan,
			'status_kode' => 'baru'
		);
	}
}

function GetTipeForm($Alokasi = '')
{
	$CI = &get_instance();
	$query = $CI->db->select("CONCAT_WS(' - ',TipeForm,Keterangan) as tipe_form_detail,CONCAT_WS('',TipeForm,Alokasi) as tipe_form_nilai,TipeForm,Alokasi ")->where('Alokasi', $Alokasi)->get('TipeForm');
	return $query;
}

function PecahAngka1($angka)
{
	$explode = explode('.', $angka);
	return $explode[0];
}
function rupiah1($angka)
{

	$hasil_rupiah = "Rp " . number_format($angka, 0, ',', ',');
	return $hasil_rupiah;
}

function MembuatMenjadiKoma($data)
{
	$array = array();
	foreach ($data as $row) {
		$array[] = $row;
	}
	return implode(",", $array);
}

function CekNoKoTmpKoDtl($NoKO = '')
{
	if ($NoKO != '') {
		$CI = &get_instance();
		$query = $CI->db->where('NoKO', $NoKO)->get('TmpKoDtl');
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	return FALSE;
}

function getDataUser()
{
	$CI = &get_instance();
	$ListSesi = $CI->session->userdata('MIS_LOGGED_TOKEN');
	$json = json_decode($ListSesi, true);
	return $json;
}
function GetFormatNumber($angka)
{
	return number_format($angka);
}
// function ShowBulan(){
// 	$namaBulan = array("Januari","Februaru","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

// 	$noBulan = 1;
// 	for($index=0; $index<12; $index++){
// 		echo "Bulan ke ". $noBulan ." : ". $namaBulan[$index] . "<br>";
// 		$noBulan++;
// 	}

// }
function CekPenerimaanAsset($JobNo, $Tahun, $bulan)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT JobNo,SUM(Netto) as Total FROM OtherIncome WHERE JobNo='$JobNo' AND YEAR(TglSetor)='$Tahun' AND MONTH(TglSetor)='$bulan' GROUP BY JobNo");

	$total = 0;
	if ($query->num_rows() > 0) {
		$row = $query->row();
		$total = $row->Total;
	}
	return $total;
}
function CekTotalPenerimaanAsset($JobNo, $Tahun)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT JobNo,SUM(Netto) as Total FROM OtherIncome WHERE JobNo='$JobNo' AND YEAR(TglSetor)='$Tahun' GROUP BY JobNo");

	$total = 0;
	if ($query->num_rows() > 0) {
		$row = $query->row();
		$total = $row->Total;
	}
	return $total;
}

function tgl_indo($tanggal)
{
	$bulan = array(
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);

	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun

	return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function GetDetailJob($JobNo)
{
	if ($JobNo != '') {
		$CI = &get_instance();
		$query = $CI->db->select('*')->from('Job')->where('JobNo', $JobNo)->get();
		return $query->row();
	}
}

function HapusFormatUang1($uang)
{
	return preg_replace('/[^A-Za-z0-9]/', '', $uang);
}
function print_rr1($data)
{
	echo "<pre>";
	print_r($data);
	echo "<pre>";
}


function DapatPembayaranBerdasarkanBulanReportPJ($NoPJ, $tahun, $bulan)
{
	$CI = &get_instance();
	$myquery = $CI->db->select('SUM(Vol*HrgSatuan) as apa')
		// ->select('Vol')
	->from('PdDtl')
	->where('NoPJ', $NoPJ)
	->where('YEAR(PjTimeEntry)', $tahun)
	->where('MONTH(PjTimeEntry)', $bulan)
	->get();

	$data = '';
	if ($myquery->num_rows() > 0) {
		$row = $myquery->row();
		$data = $row->apa;
	}

	return $data;
	// $query = $CI->db->query("SELECT SUM(Vol*HrgSatuan) as totalPdDtl FROM PdDtl WHERE NoPD='$NoPD' AND YEAR(PjTimeEntry)='$tahun' AND MONTH(PjTimeEntry)='$bulan' AND NoPJ IS NOT NULL ");

	// $query = $CI->db->query("  SELECT SUM(PJVol*PjHrgSatuan) as totalPdDtl FROM PdDtl WHERE NoPJ='$NoPJ' AND YEAR(PjTimeEntry)='$tahun' AND MONTH(PjTimeEntry)='$bulan'   ")->row();
	// foreach($query->result() as $data){
	// 	return $data->NoPD;
	// }
	// return $query->totalPdDtl;
	// return 'ok';
}

function DapatBulanTahun($data = '', $type = '')
{

	if ($data == '') {
		return '';
		exit;
	}
	$CI = &get_instance();
	$pecah = explode('-', $data);
	$bulan = $pecah[0];
	$tahun = $pecah[1];


	if ($type != '') {
		if ($type == 'bulan') {
			return $bulan;
			exit;
		} else {
			return $tahun;
			exit;
		}
	}
	return '';
}

function CekEstimasi($Estimasi)
{
	return ($Estimasi == 1) ? '(Harga Estimasi)' : '';
}

function Currency($currency)
{
	return ($currency != '') ? ($currency) : '';
}
function UbahNol($angka)
{
	return ($angka == '' || $angka == 0) ? '-' : number_format($angka);
}
function GetAksesUser($field)
{
	$CI = &get_instance();
	$data = json_decode($CI->session->userdata('MIS_LOGGED_TOKEN'), TRUE);
	return $data[$field];
}

function GetAlokasi($UserID)
{

	$CI = &get_instance();
	$getAlokasi = $CI->db->select('AksesAlokasi')->where('UserID', $UserID)->get('Login');
	if ($getAlokasi->num_rows() > 0) {
		$data = $getAlokasi->row();
		$explode_alokasi = explode(",", $data->AksesAlokasi);
		$implode_akses_alokasi = "'" . implode("','", $explode_alokasi) . "'";

		$query = $CI->db->query("SELECT * FROM Alokasi WHERE Alokasi IN(" . $implode_akses_alokasi . ") ")->result();
		return $query;
	}
}

function UbahGabunganUang1($angka)
{
	if ($angka < 0) {
		return '(' . number_format(abs($angka)) . ')';
	}

	if ($angka == '' || $angka == null || $angka == 0) {
		return '-';
	} else {
		return number_format($angka);
	}
}
function CekBLE($JobNo, $NoPD)
{

	$CI = &get_instance();
	$query = $CI->db->where(array('JobNo' => $JobNo, 'NoPD' => $NoPD))->get('BLE');
	if ($query->num_rows() > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}
function is_pesan($class = '', $message = '')
{
	$CI = &get_instance();
	if ($class != '' && $message != '') {
		$CI->session->set_flashdata('message', '<div class="alert alert-' . $class . ' alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' . $message . '</div>');
	}
}
function is_pesan_saya($class = '', $message = '')
{
	$CI = &get_instance();
	if ($class != '' && $message != '') {
		$CI->session->set_flashdata('pesan', '<div class="alert alert-' . $class . ' alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' . $message . '</div>');
	}
}
function CekBuktiPendukung($field, $value)
{
	$explode = explode(',', $field);
	$implode = implode(' ', $explode);

	$a = $implode;
	$search = strval($value);;
	if (preg_match("/{$search}/i", $a)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
function UbahPDKePJ($NoPD)
{
	$potong = substr($NoPD, 2);

	return "PJ" . $potong;
}

function GetJob($JobNo = '')
{

	$CI = &get_instance();
	$query = $CI->db->where('JobNo', $JobNo)->get('Job');
	return $query;
}

function tgl_indo_waktu($tanggal)
{
	if ($tanggal == '') {
		return '';
		exit;
	}

	if ($tanggal == '1900-01-01 00:00:00.000') {
		return NULL;
		exit;
	}


	$now = new DateTime($tanggal);
	$now2 = $now->format('H:i');
	$tgl = $now->format('Y-m-d');



	$bulan = array(
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tgl);

	return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0] . ', ' . $now2;
}
function ApproveUnapprove($app, $NoPD, $JobNo)
{
	if ($app == '') {
		return '<a type="button" onclick="ApproveUnapprove(\'' . $NoPD . '\',\'' . $JobNo . '\',\'' . $app . '\')" class="btn btn-xs btn-info  btn-outline-secondary ">APPROVE</a>';
	} else {
		return '<a type="button" onclick="ApproveUnapprove(\'' . $NoPD . '\',\'' . $JobNo . '\',\'' . $app . '\')" class="btn btn-xs btn-outline-secondary btn-danger">UNAPPROVE</a>';
	}
}


function PecahAngka($angka)
{
	$explode = explode('.', $angka);
	return $explode[0];
}
function rupiah($angka)
{

	$hasil_rupiah = "Rp " . number_format($angka, 0, ',', ',');
	return $hasil_rupiah;
}

function CariKataLoan($kata)
{
	$dapat_kata = '';
	if ($kata != '') {
		$arr = explode(' ', trim($kata));
		$dapat_kata = $arr[0];
	}

	return $dapat_kata;
}


function UbahMinus($angka)
{
	$kembalikan = $angka;
	if ($angka < 0) {
		$cek = abs($angka);
		$kembalikan = "(" . number_format($cek) . ")";
	} else {
		return $kembalikan = number_format($angka);
	}

	return $kembalikan;
}

function CekKurung($kata)
{

	if (preg_match('/[\[\]\'^£$%&*()}{@#~?><>,|=_+¬-]/', $kata)) {
		return 'ok';
	} else {
		return 'no';
	}
}

function print_rr($data)
{
	echo "<pre>";
	print_r($data);
	echo "<pre>";
}

function HapusFormatUang($uang)
{
	return preg_replace('/[^A-Za-z0-9]/', '', $uang);
}

function UbahGabunganUang($angka)
{
	if ($angka < 0) {
		return '(' . number_format(abs($angka)) . ')';
	}

	if ($angka == '' || $angka == null || $angka == 0) {
		return '-';
	} else {
		return number_format($angka);
	}
}

function UbahGabunganUang_new($angka)
{
	if ($angka < 0) {
		return '(' . number_format(abs($angka)) . ')';
	}

	if ($angka == '' || $angka == null || $angka == 0) {
		return 0;
	} else {
		return number_format($angka);
	}
}



function AksesAlokasi()
{

	$CI = &get_instance();

	$UserID = $CI->session->userdata('MIS_LOGGED_ID');
	$query = "select ax.Alokasi, bx.Keterangan from
	(select item as Alokasi 
	from
	dbo.SplitString (
	(select top 1 a.AksesAlokasi from (select * from Login) as a
	left outer join
	(select * from Alokasi) as b
	on b.Alokasi = a.AksesAlokasi
	Where a.UserID= '$UserID')
	, ',')
	) as ax
	left outer join 
	Alokasi as bx
	on bx.Alokasi = ax.Alokasi
	group by ax.Alokasi, bx.Keterangan";
	$eksekusi = $CI->db->query($query);
	return $eksekusi->result();
}

function AksesAlokasi_HanyaNamaAlokasi()
{

	$CI = &get_instance();

	$UserID = $CI->session->userdata('MIS_LOGGED_ID');
	$query = "select ax.Alokasi from
	(select item as Alokasi 
	from
	dbo.SplitString (
	(select top 1 a.AksesAlokasi from (select * from Login) as a
	left outer join
	(select * from Alokasi) as b
	on b.Alokasi = a.AksesAlokasi
	Where a.UserID= '$UserID')
	, ',')
	) as ax
	left outer join 
	Alokasi as bx
	on bx.Alokasi = ax.Alokasi
	group by ax.Alokasi";
	$eksekusi = $CI->db->query($query);
	return $eksekusi->result();
}

function AssignSpr($JobNo)
{

	$CI = &get_instance();
	// $iniNoSPr = 1;

	$NoSPR = $CI->db->query(" SELECT TOP 1 NoSPR FROM PrHdr WHERE JobNo='$JobNo' ORDER BY NoSPR DESC ");
	if ($NoSPR->num_rows() > 0) {
		$myno = $NoSPR->row();
		$nomor = substr($myno->NoSPR, -5) + 1;
		$iniNoSPr = "PR" . $JobNo . str_pad($nomor, 5, "0", STR_PAD_LEFT);
	} else {
		$iniNoSPr = "PR" . $JobNo . str_pad(1, 5, "0", STR_PAD_LEFT);
	}

	return $iniNoSPr;
}

function GetRekening()
{

	$CI = &get_instance();
	$UserID = $CI->session->userdata('MIS_LOGGED_ID');
	$getRek = $CI->db->select('AksesRekening')->where('UserID', $UserID)->get('Login');
	if ($getRek->num_rows() > 0) {
		$data = $getRek->row();
		$explode_alokasi = explode(",", $data->AksesRekening);
		$implode_akses_alokasi = "'" . implode("','", $explode_alokasi) . "'";

		// $query = $CI->db->query("SELECT * FROM Rekening WHERE LedgerNo IN(" . $implode_akses_alokasi . ") ")->result();
		$Company = $CI->config->item('Company');
		$query = $CI->db->query("SELECT * FROM Rekening  Where Company='$Company' ")->result();
		return $query;
	}
}

function GetDetailJobWhereField($JobNo, $field)
{
	if ($JobNo != '') {
		$CI = &get_instance();
		$query = $CI->db->select($field)->from('Job')->where('JobNo', $JobNo)->get();
		return $query->row();
	}
}


function ApproveAndUnapprovedSPR($JobNo, $NoSPR, $ApprovedBy)
{
	$CI = &get_instance();


	if ($ApprovedBy == '') {
		return '<button class="badge badge-xs btn-success" onclick="getApprove(\'' . $JobNo . '\',\'' . $NoSPR . '\',\'' . $ApprovedBy . '\')">Approved</button>';
	} else {
		return '<button class="badge badge-xs btn-secondary" onclick="getApprove(\'' . $JobNo . '\',\'' . $NoSPR . '\',\'' . $ApprovedBy . '\')">UnApproved</button>';
	}
}

function dapatkan_PPH()
{
	$my_pph = 0.97;
	return $my_pph;
}
function dapatkan_PPN()
{
	$my_ppn = 1.1;
	return $my_ppn;
}

function TglIndoGarisMiring_tanpa_waktu($mytgl)
{
	if ($mytgl == '') {
		return '';
	} else {
		$UbahTgl = date('d/M/Y', strtotime($mytgl));
		return $UbahTgl;
	}
}


function TglIndoGarisMiring_dengan_waktu($mytgl)
{

	if ($mytgl == '') {
		return '';
	} else {
		$UbahTgl = date('d/M/Y H:i', strtotime($mytgl));
		return $UbahTgl;
	}
}

function TglIndoSTRIP_dengan_waktu($mytgl)
{

	if ($mytgl == '') {
		return '';
	} else {
		$UbahTgl = date('d-M-Y H:i', strtotime($mytgl));
		return $UbahTgl;
	}
}

function TglStrip_tanpa_waktu($mytgl)
{
	if ($mytgl == '') {
		return '';
	} else {
		$UbahTgl = date('d-M-Y', strtotime($mytgl));
		return $UbahTgl;
	}
}


function is_login()
{

	$CI = &get_instance();
	if ($CI->session->userdata('MIS_LOGGED_ID') == '') {
		session_destroy();
		redirect('Auth', 'refresh');
	}
}

function UangBiasa($uang)
{
	return number_format($uang);
}

function TglPrint($tgl)
{
	return date('d-M-Y', strtotime($tgl));
}

function AlokasiBiasa($Alokasi)
{
	$CI = &get_instance();
	$query = $CI->db->where('Alokasi', $Alokasi)->get('Alokasi')->row_array();
	return $query['Alokasi'] . ' - ' . $query['Keterangan'];
}
function dapatkan_tipeform($TipeForm)
{
	$CI = &get_instance();
	$query = $CI->db->where('TipeForm', $TipeForm)->get('TipeForm')->row_array();
	return $query['TipeForm'] . ' - ' . $query['Keterangan'];
}

function dapatkan_id_vendor($NoKO)
{
	$CI = &get_instance();
	$query =  $CI->db->query("
		SELECT B.VendorId FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE NoKO='$NoKO'
		")->row_array();
	return $query['VendorId'];
}

function cek_INT($NoKO)
{
	$CI = &get_instance();
	$data = substr($NoKO, '-3');
	return $data;
}

function dapat_no_urut_table_pdhdr($NoPD)
{
	$CI = &get_instance();
	$query = $CI->db->select('MAX(NoUrut) as Norut')
	->where('NoPD', $NoPD)
	->get('PdDtl');
	$Norut = 1;

	if ($query->num_rows() > 0) {
		$row = $query->row_array();
		$Norut = (int) $row['Norut'] + 1;
	} else {
		$Norut = 1;
	}
	return $Norut;
}


function cek_pd_sudah_sampai_kasir_belum($NoPD)
{
	$CI = &get_instance();
	$query = $CI->db->select('NoPD')
	->where('NoPD', $NoPD)
	->get('BLE');
	if ($query->num_rows() > 0) {
		return 'sudah_pembayaran';
	} else {
		return 'belum_pembayaran';
	}
}

function cek_pd_sudah_sampai_kk($NoPD)
{
	$CI = &get_instance();

	$query = $CI->db->select('ApprovedByKK')
	->where('NoPD', $NoPD)
	->get('PdHdr')->row_array();

	if ($query['ApprovedByKK'] == NULL || $query['ApprovedByKK'] == '') {
		return 'belum_app_kk';
	} else {
		return 'sudah_app_kk';
	}
}

function cek_pd_sudah_sampai_kt($NoPD)
{
	$CI = &get_instance();

	$query = $CI->db->select('ApprovedByKT')
	->where('NoPD', $NoPD)
	->get('PdHdr')->row_array();

	if ($query['ApprovedByKT'] == NULL || $query['ApprovedByKT'] == '') {
		return 'belum_app_kt';
	} else {
		return 'sudah_app_kt';
	}
}

function OtomatisUbahCounter($JobNo, $Alokasi, $status_kode, $status_tambah = '')
{

	$CI = &get_instance();

	$where_counter = array(
		'JobNo' => $JobNo,
		'Alokasi' => $Alokasi,
	);

	$data_counter = array(
		'JobNo' => $JobNo,
		'Alokasi' => $Alokasi,
		'UserEntry' => $CI->session->userdata("MIS_LOGGED_ID"),
		'TimeEntry' => date('Y-m-d H:i:s'),
	);

	if ($status_kode == 'baru') {

		$nama_counter = '';

		if ($status_tambah == 'RKD') {
			$nama_counter = 'CounterKSO';
		} else if ($status_tambah == 'MIX') {
			$nama_counter = 'CounterPDMIX';
		} else {
			$nama_counter = 'CounterPD';
		}

		$data_counter[$nama_counter] = 1;
		$CI->db->insert('Counter', $data_counter);
		// return 'ok';

	} else {

		$nama_counter = '';
		if ($status_tambah == 'RKD') {
			$nama_counter = 'CounterKSO';
		} else if ($status_tambah == 'MIX') {
			$nama_counter = 'CounterPDMIX';
		} else {
			$nama_counter = 'CounterPD';
		}

		$query = $CI->db->select($nama_counter)->where($where_counter)->get('Counter');
		if ($query->num_rows() > 0) {
			$CounterRow = $query->row_array();

			$getCounterPD = $CounterRow[$nama_counter];
			$data_counter[$nama_counter] = (int) $getCounterPD + 1;
		}
		$CI->db->set($data_counter)->where($where_counter)->update('Counter');
		// return 'ini';

	}
	// return $data_counter;


}

function dapatkan_foto_karyawan_dari_sistem_lain($NIK, $pribadi = '')
{
	$CI = &get_instance();

	// $FotoNama = 'assets/foto_karyawan/user.jpg';
	$FotoNama = 'assets/foto_karyawan/user.jpg';
	$where_cek = NULL;
	if ($pribadi != '' or $pribadi != NULL) {
		$where_cek = " AND StatusFoto='Pribadi' ";
	}


	$cek_foto = $CI->db->query("SELECT TOP 1 * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND TipeFile='Foto'  " . $where_cek . " ");



	if ($cek_foto->num_rows() > 0) {
		$row = $cek_foto->row_array();
		if ($row['NamaFile'] != NULL || $row['NamaFile'] != '') {
			$FotoNama = 'assets/foto_karyawan/' . $row['NamaFile'];
		}
	} else {
		$FotoNama = 'assets/foto_karyawan/user.jpg';
	}

	return  $FotoNama;
}


function dapatkan_foto_karyawan($NIK)
{
	$CI = &get_instance();

	$FotoNama = 'assets/foto_karyawan/user.jpg';
	$cek_foto = $CI->db->query("SELECT TOP 1 * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND TipeFile='Foto'  ");
	if ($cek_foto->num_rows() > 0) {
		$row = $cek_foto->row_array();
		$FotoNama = 'assets/foto_karyawan/' . $row['NamaFile'];
	}

	return $FotoNama;
}

function dapatkan_foto_karyawan_baru($NIK, $pribadi = '')
{
	$CI = &get_instance();

	// $Company = 'KIP';

	$FotoNama = 'assets/foto_karyawan/user.jpg';

	$where_cek = NULL;
	if ($pribadi != '' or $pribadi != NULL) {
		$where_cek = " AND StatusFoto IS NOT NULL OR StatusFoto !='' ";
	}


	$cek_foto = $CI->db->query("SELECT TOP 1 * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND TipeFile='Foto' " . $where_cek	. "  ");
	if ($cek_foto->num_rows() > 0) {
		$row = $cek_foto->row_array();
		$FotoNama = 'assets/foto_karyawan/' . $row['NamaFile'];
	}

	if ($CI->config->item('Company') != 'MDH') {
		$FotoNama = 'assets/foto_karyawan/Tomy_Bani_Adam_221123111119_Foto.PNG';
	}

	return $FotoNama;
}


function CekAkses_dan_field_pada_database()
{
	$CI = &get_instance();

	if (!$CI->db->field_exists('Akses_DataProyek', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Akses_DataProyek char(1) NULL DEFAULT((0))");
	}

	if (!$CI->db->field_exists('Akses_DataKontrak', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Akses_DataKontrak char(1) NULL DEFAULT((0))");
	}

	if (!$CI->db->field_exists('Akses_TataKelola', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Akses_TataKelola char(1) NULL DEFAULT((0))");
	}

	if (!$CI->db->field_exists('Akses_LeafletProyek', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Akses_LeafletProyek char(1) NULL DEFAULT((0))");
	}

	if (!$CI->db->field_exists('Akses_MOS_In_Out', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Akses_MOS_In_Out char(1) NULL DEFAULT((0))");
	}

	if (!$CI->db->field_exists('Akses_MOS_list', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Akses_MOS_list char(1) NULL DEFAULT((0))");
	}

	if (!$CI->db->field_exists('Akses_ApprovalMOS', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Akses_ApprovalMOS char(1) NULL DEFAULT((0))");
	}
}


function Tampil_Job_Berdasarkan_AksesJob()
{
	$CI = &get_instance();

	$Company = $CI->config->item('Company');
	$UserID = $CI->session->userdata('MIS_LOGGED_ID');

	$myquery = "
	SELECT ax.JobNo, bx.JobNm FROM
	(SELECT item AS JobNo
		FROM
		dbo.SplitString ((SELECT TOP 1 a.AksesJob from (SELECT * FROM Login) AS a
			LEFT OUTER JOIN
			(SELECT * FROM Job) AS b
			ON b.JobNo = a.AksesJob
			WHERE a.UserID= '$UserID'), ',')) AS ax
	LEFT OUTER JOIN 
	Job AS bx
	ON bx.JobNo = ax.JobNo WHERE bx.Company='$Company' GROUP BY ax.JobNo, bx.JobNm";

	$ekse_query = $CI->db->query($myquery);
	return $ekse_query->result();
}

function AksesJob_HanyaJobNumber()
{
	$CI = &get_instance();

	$UserID = $CI->session->userdata('MIS_LOGGED_ID');

	$myquery = "
	SELECT ax.JobNo FROM
	(SELECT item AS JobNo
		FROM
		dbo.SplitString ((SELECT TOP 1 a.AksesJob from (SELECT * FROM Login) AS a
			LEFT OUTER JOIN
			(SELECT * FROM Job) AS b
			ON b.JobNo = a.AksesJob
			WHERE a.UserID= '$UserID'), ',')) AS ax
	LEFT OUTER JOIN 
	Job AS bx
	ON bx.JobNo = ax.JobNo GROUP BY ax.JobNo";

	$ekse_query = $CI->db->query($myquery);
	return $ekse_query->result();
}



// ----------------------------------------------------------------------------------------------------------
function pembuatan_tbl_InvPD_sementara()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('InvPD_sementara')) {
		$CI->db->query(
			"
			CREATE TABLE InvPD_sementara(
				JobNo nvarchar(10) NOT NULL ,
				NoKO nvarchar(15) NOT NULL ,
				InvNo nvarchar(50) NOT NULL,
				NoPD nvarchar(20) NOT NULL,
				PaymentAmount money NULL , 
				UserEntry nvarchar(30) NULL,
				TimeEntry datetime NULL,
				Company nvarchar(3) NULL
			)"
		);
	}
}

function pembuatan_tbl_invoice_sementara()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('tbl_invoice_sementara')) {

		$CI->db->query("CREATE TABLE tbl_invoice_sementara(
			[JobNo] [nvarchar](10) NOT NULL,
			[NoKO] [nvarchar](15) NOT NULL,
			[InvNo] [nvarchar](50) NOT NULL,
			[InvDate] [date] NULL,
			[DueDate] [date] NULL,
			[PPN] [money] NULL,
			[FPNo] [nvarchar](50) NULL,
			[FPDate] [date] NULL,
			[Total] [money] NULL,
			[Keterangan] [nvarchar](255) NULL,
			[UserEntry] [nvarchar](30) NULL,
			[TimeEntry] [datetime] NULL,
			[NoSJ] [nvarchar](1000) NULL,
			[LedgerNo] [bigint] NULL,
			[PPH] [money] NULL,
			[TotalPayment] [money] NULL,
			[Company] [nvarchar](3) NULL,
		)");
	}
}

function tambah_ledgerNo_di_InvPD_sementara()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('LedgerNo', 'InvPD_sementara')) {
		$CI->db->query("ALTER TABLE InvPD_sementara ADD LedgerNo bigint NULL");
	}
}

function tambah_ledgerNo_di_InvPD()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('LedgerNo', 'InvPD')) {
		$CI->db->query("ALTER TABLE InvPD ADD  LedgerNo BigInt identity");
	}
}



function pembuatan_tbl_PdDtl_sementara()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('PdDtl_sementara')) {
		$CI->db->query("

			CREATE TABLE [dbo].[PdDtl_sementara](
				[NoPD] [nvarchar](20) NOT NULL,
				[NoUrut] [int] NULL,
				[KdRAP] [nvarchar](10) NULL,
				[Uraian] [nvarchar](300) NULL,
				[Vol] [decimal](10, 3) NULL,
				[Uom] [nvarchar](15) NULL,
				[HrgSatuan] [money] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[NoPJ] [nvarchar](20) NULL,
				[PjUraian] [nvarchar](2000) NULL,
				[PjVol] [decimal](10, 3) NULL,
				[PjHrgSatuan] [money] NULL,
				[PjUserEntry] [nvarchar](30) NULL,
				[PjTimeEntry] [datetime] NULL,
				[Company] [nvarchar](10) NULL
				)

			");
	}
}

function uang_koma_genap_decimal($uang = 0)
{
	$CI = &get_instance();
	return number_format((float)$uang, 0, '.', ',');
}
function uang_koma_genap_decimal_kedua($nilai)
{
	$nilai = round($nilai, 2);
	return number_format($nilai, 2);
}


function cek_status_PD_untuk_LAPANGAN($LedgerNo = '')
{
	if ($LedgerNo == '') {
		return '';
	}

	$CI = &get_instance();
	$query_PD = $CI->db->query("
		SELECT 
		CASE  
		WHEN (ApprovedByAK IS NOT NULL OR ApprovedByAK !='') THEN 'adaAK'
		ELSE 'kosongAK'
		END AS 'ApprovedByAK_validasi',
		CASE  
		WHEN (ApprovedByKT IS NOT NULL OR ApprovedByKT !='') THEN 'adaKT'
		ELSE 'kosongKT'
		END AS 'ApprovedByKT_validasi',
		CASE  
		WHEN (ApprovedByKK IS NOT NULL OR ApprovedByKK !='') THEN 'adaKK'
		ELSE 'kosongKK'
		END AS 'ApprovedByKK_validasi'
		FROM PdHdr WHERE LedgerNo='$LedgerNo' ")->row_array();
	return $query_PD;
}

function job_data($JobNo)
{
	$CI = &get_instance();
	$query =  $CI->db->query(" SELECT JobNo, JobNm, Lokasi FROM Job WHERE JobNo='$JobNo' ")->row();
	return $query;
}

function cek_pj_saya($NoPJ = null, $data_asli)
{
	return ($NoPJ == '' || $NoPJ == NULL) ? '' : $data_asli;
}

function pembuatan_field_logo_baru()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('Logo_baru', 'Job')) {
		$CI->db->query("ALTER TABLE Job ADD Logo_baru ntext NULL ");
	}
}

function update_logo_company($JobNo = '')
{
	if ($JobNo == '' || $JobNo == NULL) {
		exit;
	}

	$CI = &get_instance();

	$q = $CI->db->select("Logo_baru")->where('JobNo', $JobNo)->get('Job')->row_array();
	if ($q['Logo_baru'] != NULL || $q['Logo_baru'] != NULL) {

		if (file_exists('assets/doc/' . $q['Logo_baru'])) {
			unlink('assets/doc/' . $q['Logo_baru']);
		}
	}
}

function tambah_field_password_di_tbl_karyawan()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('Password_dari_CI', 'Karyawan')) {
		$CI->db->query("ALTER TABLE Karyawan ADD Password_dari_CI ntext NULL");
	}

	if (!$CI->db->field_exists('Password_dari_CI', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD Password_dari_CI ntext NULL");
	}
}



function alamat_server_dan_local_dan_folder($type = '')
{
	// jika kosong maka tipenya jadi localhost, jika tidak maka server
	$myurl = NULL;
	if ($type == '' || $type == 'local') {
		$myurl = 'http://127.0.0.1/MDH_07_Des2022/';

		//utk di sistem pak mul
		// $myurl = 'http://127.0.0.1:8081/MDH_07_Des2022/';

	} else {
		$myurl = 'http://147.139.178.49/mdh_ver1.1/';
	}
	return $myurl;
}

function url_utk_document_root_dan_nama_folder($type = '')
{

	$direktori = NULL;
	if ($type == '' || $type == 'local') {
		$direktori = $_SERVER['DOCUMENT_ROOT'] . '/MDH_07_Des2022/assets/foto_karyawan/';
	} else {
		$direktori = $_SERVER['DOCUMENT_ROOT'] . '/mdh_ver1.1/assets/foto_karyawan/';
	}
	return $direktori;
}
function hapus_file_untuk_document_root($type = '', $NamaFile = '')
{

	$akses_folder = NULL;



	if ($type == '' || $type == 'local') {
		if ($NamaFile == '') {
			$akses_folder = $_SERVER['DOCUMENT_ROOT'] . '/MDH_07_Des2022/assets/dokumen_pendukung_karyawan/';
		} else {
			$akses_folder = $_SERVER['DOCUMENT_ROOT'] . '/MDH_07_Des2022/assets/dokumen_pendukung_karyawan/' . $NamaFile;
		}
	} else {

		if ($NamaFile == '') {
			$akses_folder = $_SERVER['DOCUMENT_ROOT'] . '/mdh_ver1.1/assets/dokumen_pendukung_karyawan/';
		} else {
			$akses_folder = $_SERVER['DOCUMENT_ROOT'] . '/mdh_ver1.1/assets/dokumen_pendukung_karyawan/' . $NamaFile;
		}
	}
	return $akses_folder;
}

function atur_null($data)
{
	if ($data == NULL || $data == '' || $data == '01-Jan-1970 07:00') {
		return NULL;
	} else {
		return $data;
	}
}


function pembuatan_tbl_notif()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('MyNotif')) {
		// $CI->db->query("

		// 	CREATE TABLE [dbo].[MyNotif](
		// 		[NotifID] [bigint] IDENTITY(1,1) NOT NULL,
		// 		[LedgerNo] [int] NULL,
		// 		[NIKPembuat] [nvarchar](6) NULL,
		// 		[NamaPembuat] [nvarchar](100) NULL,
		// 		[NIKPenerima] [nvarchar](6) NULL,
		// 		[NamaPenerima] [nvarchar](100) NULL,
		// 		[DiBaca] [int] NULL,
		// 		[DariTable] [nvarchar](100) NULL,
		// 		[UserEntry] [nvarchar](30) NULL,
		// 		[TimeEntry] [datetime] NULL,
		// 		[UrlTujuan] [nvarchar](100) NULL,
		// 		[Keterangan] [ntext] NULL,
		// 	PRIMARY KEY CLUSTERED 
		// 	(
		// 		[NotifID] ASC
		// 	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
		// 	) ON [PRIMARY]
		// ");


	}
}

function tbl_dokumen_pendukung()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('tbl_dokumen_pendukung')) {
		$CI->db->query("
			CREATE TABLE tbl_dokumen_pendukung(
				LedgerNo bigint IDENTITY(1,1) NOT NULL,
				NIK nvarchar(6) NULL,
				NamaFile nvarchar(100) NULL,
				TipeFile nvarchar(100) NULL,
				KeteranganFile nvarchar(100) NULL,
				UserEntry nvarchar(100) NULL,
				TimeEntry datetime NULL

			) ");
	}
}

function dapat_nama_karyawan($NIK)
{
	$CI = &get_instance();
	$query = $CI->db->select('Nama')->from('Karyawan')->where(array('NIK' => $NIK))->get();
	$row = $query->row();
	return $row->Nama;
}


function get_nama_karyawan($NIK = '')
{
	$CI = &get_instance();
	if ($NIK == '' || $NIK == NULL) {
		return NULL;
		exit;
	}

	$query = $CI->db->query("SELECT Nama FROM Karyawan WHERE NIK='$NIK' ");
	$Nama = NULL;
	if ($query->num_rows() > 0) {
		$row = $query->row();
		return $row->Nama;
	} else {
		return NULL;
	}
}

function cek_KSO_Job($JobNo)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT KSO FROM Job WHERE JobNo='$JobNo' ")->row();
	return $query->KSO;
}

function dapat_berdasarkan_field_dan_JobNo($field, $JobNo)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT " . $field . " FROM Job WHERE JobNo='$JobNo' ")->row();
	return $query->$field;
}

function UangTerbaru($data)
{
	return number_format($data, 2, '.', ',');
}
function VolKetik($data)
{
	return number_format($data, 3, '.', ',');
}
function Pembuatan_table_SjHdr_Cadangan()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('SjHdr_Cadangan')) {
		$CI->db->query("
			
			
			CREATE TABLE [dbo].[SjHdr_Cadangan](
				[LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
				[NoSJ] [nvarchar](20) NOT NULL,
				[TglSJ] [date] NULL,
				[NoKO] [nvarchar](15) NULL,
				[JobNo] [nvarchar](10) NULL,
				[ApprovedBy] [nvarchar](30) NULL,
				[TimeApproved] [datetime] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[NoSJVendor] [nvarchar](50) NULL,
				[InvNo] [nvarchar](50) NULL,
				[NoSJSebelumnya] [nvarchar](20) NULL,
				CONSTRAINT [PK_SjHdr_SjHdr_Cadangan] PRIMARY KEY CLUSTERED 
				(
					[NoSJ] ASC
					)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]
			

			");
	}

	if (!$CI->db->field_exists('NoSJSebelumnya', 'SjHdr_Cadangan')) {
		$CI->db->query("ALTER TABLE SjHdr_Cadangan ADD NoSJSebelumnya nvarchar(20)  NOT NULL");
	}
}


function Pembuatan_table_SjDtl_Cadangan()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('SjDtl_Cadangan')) {
		$CI->db->query("

			CREATE TABLE [dbo].[SjDtl_Cadangan] (
				[LedgerNo] bigint  IDENTITY(1,1) NOT NULL,
				[NoSJ] nvarchar(20)  NOT NULL,
				[NoUrut] int DEFAULT ((0)) NOT NULL,
				[Alokasi] nvarchar(1)   NULL,
				[KdRAP] nvarchar(10)   NULL,
				[Uraian] nvarchar(255)   NULL,

				[Uom] nvarchar(15)  NULL,
				[HrgSatuan] money DEFAULT ((0)) NULL,
				[UserEntry] nvarchar(30)  NULL,
				[TimeEntry] datetime  NULL,

				[JobNo] [nvarchar](10) NULL,
				[NoKO] [nvarchar](15) NULL,
				[VolAsli] decimal(10,3) DEFAULT ((0)) NULL,
				[VolTerpakai] decimal(10,3) DEFAULT ((0)) NULL,
				[Vol] decimal(10,3) DEFAULT ((0)) NULL,
				[VolKO] decimal(10,3) DEFAULT ((0)) NULL,
				[VolTerima] decimal(10,3) DEFAULT ((0)) NULL,

				)
			
			");
	}

	if ($CI->db->table_exists('SjDtl_Cadangan')) {

		if (!$CI->db->field_exists('VolAsli', 'SjDtl_Cadangan')) {
			$CI->db->query("ALTER TABLE SjDtl_Cadangan ADD VolAsli [decimal](10, 3) NULL");
		}

		if (!$CI->db->field_exists('VolTerpakai', 'SjDtl_Cadangan')) {
			$CI->db->query("ALTER TABLE SjDtl_Cadangan ADD VolTerpakai [decimal](10, 3) NULL");
		}

		if (!$CI->db->field_exists('JobNo', 'SjDtl_Cadangan')) {
			$CI->db->query("ALTER TABLE SjDtl_Cadangan ADD JobNo [nvarchar](10) NULL");
		}

		if (!$CI->db->field_exists('NoKO', 'SjDtl_Cadangan')) {
			$CI->db->query("ALTER TABLE SjDtl_Cadangan ADD NoKO [nvarchar](15) NULL");
		}
		if (!$CI->db->field_exists('VolTerima', 'SjDtl_Cadangan')) {
			$CI->db->query("ALTER TABLE SjDtl_Cadangan ADD VolTerima [decimal](10, 3) NULL");
		}
		if (!$CI->db->field_exists('VolKO', 'SjDtl_Cadangan')) {
			$CI->db->query("ALTER TABLE SjDtl_Cadangan ADD VolKO [decimal](10, 3) NULL");
		}
	}
}

function Pembuatan_table_KoDtl_Cadangan()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('KoDtl_Cadangan')) {
		$CI->db->query("
			
			CREATE TABLE [KoDtl_Cadangan](
				[NoKO] [nvarchar](15) NOT NULL,
				[NoUrut] [int] NULL,
				[Alokasi] [nvarchar](1) NULL,
				[KdRAP] [nvarchar](10) NULL,
				[Uraian] [nvarchar](255) NULL,
				[Vol] [decimal](10, 3) NULL,
				[Uom] [nvarchar](15) NULL,
				[HrgSatuan] [money] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[JobNo] [nvarchar](15) NULL
				) ON [PRIMARY]


			");
	}
}

function Pembuatan_tbl_FileMOS()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('tbl_FileMOS')) {
		$CI->db->query("
			
			CREATE TABLE [tbl_FileMOS](
				[LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
				[NoSJ] [nvarchar](20) NULL,
				[NamaFile] [text] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				)


			");
	}
}

function membuatFolderMosFile()
{
	$CI = &get_instance();
	if (!is_dir('assets/MosFile')) {
		mkdir('assets/MosFile', 0777, TRUE);
	}
}

function Pembuatan_Table_Pakai_MOS()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('tbl_PakaiMOS')) {
		$CI->db->query("

			CREATE TABLE [dbo].[tbl_PakaiMOS] (
				[LedgerNo] bigint  IDENTITY(1,1) NOT NULL,
			-- [NoSJ] nvarchar(20)  NOT NULL,
			[NoUrut] int DEFAULT ((0)) NOT NULL,
			[Alokasi] nvarchar(1)   NULL,
			[KdRAP] nvarchar(10)   NULL,
			[Uraian] nvarchar(255)   NULL,
			
			[Uom] nvarchar(15)  NULL,
			[HrgSatuan] money DEFAULT ((0)) NULL,
			[UserEntry] nvarchar(30)  NULL,
			[TimeEntry] datetime  NULL,
			
			[JobNo] [nvarchar](10) NULL,
			[NoKO] [nvarchar](15) NULL,
			[VolPakai] decimal(10,3) DEFAULT ((0)) NULL,

			)
			
			");
	}
}



function Pembuatan_table_SjHdr_Cadangan_HISTORY()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('SjHdr_Cadangan_History')) {
		$CI->db->query("
			
			CREATE TABLE [dbo].[SjHdr_Cadangan_History](
				[LedgerNo] [nvarchar](10) NULL,
				[NoSJ] [nvarchar](20) NULL,
				[TglSJ] [date] NULL,
				[NoKO] [nvarchar](15) NULL,
				[JobNo] [nvarchar](10) NULL,
				[ApprovedBy] [nvarchar](30) NULL,
				[TimeApproved] [datetime] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[NoSJVendor] [nvarchar](50) NULL,
				[InvNo] [nvarchar](50) NULL,
				[NoSJSebelumnya] [nvarchar](20) NULL,
				[NoID] bigint IDENTITY(1,1) NOT NULL,
				)
			");
	}

	if ($CI->db->table_exists('SjHdr_Cadangan_History')) {

		if (!$CI->db->field_exists('NoID', 'SjHdr_Cadangan_History')) {
			$CI->db->query("ALTER TABLE SjHdr_Cadangan_History ADD NoID bigint IDENTITY(1,1) NOT NULL");
		}
	}
}



function Pembuatan_table_SjDtl_Cadangan_History()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('SjDtl_Cadangan_History')) {
		$CI->db->query("

			CREATE TABLE [dbo].[SjDtl_Cadangan_History] (
				[LedgerNo] [nvarchar](10) NULL,
				[NoSJ] nvarchar(20) NULL,
				[NoUrut] int DEFAULT ((0)) NOT NULL,
				[Alokasi] nvarchar(1)   NULL,
				[KdRAP] nvarchar(10)   NULL,
				[Uraian] nvarchar(255)   NULL,
				[Uom] nvarchar(15)  NULL,
				[HrgSatuan] money DEFAULT ((0)) NULL,
				[UserEntry] nvarchar(30)  NULL,
				[TimeEntry] datetime  NULL,
				[JobNo] [nvarchar](10) NULL,
				[NoKO] [nvarchar](15) NULL,
				[VolAsli] decimal(10,3) DEFAULT ((0)) NULL,
				[VolTerpakai] decimal(10,3) DEFAULT ((0)) NULL,
				[Vol] decimal(10,3) DEFAULT ((0)) NULL,
				[VolKO] decimal(10,3) DEFAULT ((0)) NULL,
				[VolTerima] decimal(10,3) DEFAULT ((0)) NULL,
				[NoID] bigint IDENTITY(1,1) NOT NULL,
				)

			");
	}

	if ($CI->db->table_exists('SjDtl_Cadangan_History')) {

		if (!$CI->db->field_exists('NoID', 'SjDtl_Cadangan_History')) {
			$CI->db->query("ALTER TABLE SjDtl_Cadangan_History ADD NoID bigint IDENTITY(1,1) NOT NULL");
		}
	}
}

function tblBarangMasuk_Header_Pembuatan()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('tblBarangMasuk_Header')) {
		$CI->db->query("
			
			CREATE TABLE [dbo].[tblBarangMasuk_Header] (
				[LedgerNo] bigint  IDENTITY(1,1) NOT NULL,
				[NoSJ] nvarchar(20) NOT NULL,
				[TglSJ] date  NULL,
				[NoKO] nvarchar(15)  NULL,
				[JobNo] nvarchar(10) NULL,
				[ApprovedBy] nvarchar(30) NULL,
				[TimeApproved] datetime  NULL,
				[UserEntry] nvarchar(30) NULL,
				[TimeEntry] datetime  NULL,
				[NoSJVendor] nvarchar(50) NULL
				)
			

			");
	}
}

function tblBarangMasuk_Detail_Pembuatan()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('tblBarangMasuk_Detail')) {
		$CI->db->query("
			
			CREATE TABLE [dbo].[tblBarangMasuk_Detail] (
				[LedgerNo] bigint  IDENTITY(1,1) NOT NULL,
				[NoSJ] nvarchar(20) NOT NULL,
				[NoUrut] int  NOT NULL,
				[Alokasi] nvarchar(1) NULL,
				[KdRAP] nvarchar(10) NULL,
				[Uraian] nvarchar(255) NULL,
				[Uom] nvarchar(15)  NULL,
				[HrgSatuan] money  NULL,
				[JumlahBarang] decimal(10,3)  NULL,
				[UserEntry] nvarchar(30)  NULL,
				[TimeEntry] datetime  NULL,
				[JobNo] nvarchar(10)  NOT NULL,
				[VolKO] decimal(10,3)  NULL,
				[VolBrgMasuk] decimal(10,3)  NULL,
				[Keterangan] text  NULL,
				[ApprovedBy] nvarchar(30) NULL,
				[TimeApproved] datetime  NULL,
				[NoKO] nvarchar(15)  NULL,
				
				)
			

			");
	}
}


function tblPemakaian_MOS_Pembuatan()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('tblPemakaian_MOS')) {
		$CI->db->query("
			
			CREATE TABLE [dbo].[tblPemakaian_MOS] (
				[LedgerNo] bigint  NULL,
				[NoMK] nvarchar(20) NOT NULL,
				[NoSJ] nvarchar(20) NULL,

				[NoUrut] int  NOT NULL,
				[Alokasi] nvarchar(1) NULL,
				[KdRAP] nvarchar(10) NULL,
				[Uraian] nvarchar(255)  NULL,
				[Uom] nvarchar(15) NULL,
				[HrgSatuan] money  NULL,
				[JumlahBarang] decimal(10,3) NULL,
				[JobNo] nvarchar(10)    NOT NULL,
				[VolKO] decimal(10,3)  NULL,
				[VolBrgMasuk] decimal(10,3)  NULL,
				[Keterangan] text    NULL,
				[ApprovedBy] nvarchar(30)    NULL,
				[TimeApproved] datetime  NULL,
				[JumlahDipakai] decimal(10,3)  NULL,
				[MyID] bigint  IDENTITY(1,1) NOT NULL,
				[UserEntry] nvarchar(30)    NULL,
				[TimeEntry] datetime  NULL,
				[ApprovedPemakaian] nvarchar(30)  NULL,
				[TimeApprovedPemakaian] datetime  NULL,
				[NoKO] [nvarchar](15) NOT NULL,
				)
			

			");
	}
}

function ShowKodeAman($nilai)
{
	return htmlentities($nilai);
}

function ShowKodeAman_Kedua($nilai)
{
	return ($nilai == '' || $nilai == NULL) ? '' : htmlentities($nilai);
}


function VolDecimal_Nol_Tiga($data)
{
	return number_format($data, 3, '.', ',');
}
function VolDecimal_Nol_Dua($data)
{
	return number_format($data, 2, '.', ',');
}

function CekDanHapusKoma($nilai)
{
	$CI = &get_instance();
	$nilai = str_replace(",", "", $nilai);
	return $nilai;
}

function tbl_PdDtl_buatan()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('tbl_PdDtl_buatan')) {
		$CI->db->query("CREATE TABLE tbl_PdDtl_buatan(
			[NoUrut] [int] NULL,	
			[Tipe] [nvarchar](100) NULL, 
			[KdRAP] [nvarchar](10) NULL, 
			[Uraian] [nvarchar](255) NULL, 
			[Vol] [decimal](10, 3) NULL,
			[Uom] [nvarchar](15) NULL,
			[HrgSatuan] [money] NULL,
			[UserID] [nvarchar](30) NULL,
			[NoKO] [nvarchar](15) NULL,
		)");
	}
}

function Pembuatan_NoPD($JobNo, $Alokasi, $TypePD)
{
	$CI = &get_instance();
	$UserEntry = $CI->session->userdata('MIS_LOGGED_NAME');
	$TimeEntry = date('Y-m-d H:i:s');

	$TxtQuery = "";
	$TxtNoPD = "";
	$TxtCounter = "";
	// $JobNo='24001';

	$CI->db->trans_begin();


	if ($TypePD == 'PDRKD') {
		$TxtNoPD = "PDRKD";
		$TxtCounter = "CounterKSO";
	} else if ($TypePD == 'PDMIX') {
		$TxtNoPD = "PDMIX";
		$TxtCounter = "CounterPDMIX";
	} else {
		$TxtNoPD = "PD";
		$TxtCounter = "CounterPD";
	}

	$TxtQuery = "SELECT " . $TxtCounter . " FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'";
	$query = $CI->db->query($TxtQuery);
	$JumlahData = $query->num_rows();
	$rowPD = $query->row_array();

	$NoUrut = 1;
	$Kombinasi = $TypePD . $JobNo . $Alokasi;
	if ($JumlahData > 0) {
		$NoUrut = $rowPD[$TxtCounter] + 1;

		$CI->db->query("UPDATE Counter SET " . $TxtCounter . "='$NoUrut',UserEntry='$UserEntry',TimeEntry='$TimeEntry' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'");


		$NoUrut = sprintf("%05s", $NoUrut);
	} else {
		$NoUrut = 1;
		$NoUrut = sprintf("%05s", $NoUrut);
		$CI->db->query("INSERT INTO Counter(JobNo,Alokasi," . $TxtCounter . ",UserEntry,TimeEntry) VALUES ('$JobNo','$Alokasi',1,'$UserEntry','$TimeEntry')");
	}

	if ($CI->db->trans_status() === FALSE) {
		$CI->db->trans_rollback();
		return 'gagal';
	} else {
		$CI->db->trans_commit();
		$Kombinasi = $Kombinasi . $NoUrut;
		return $Kombinasi;
	}





	// return 

	// return $TxtQuery;


}

function UbahDecimal_Nol_Lima($data)
{
	if ($data == 0 || $data == '' || $data == NULL) {
		return number_format(0);
	} else {
		return number_format($data, 5, '.', ',');
	}
}

function UbahDecimal_Nol_Dua($data)
{
	if ($data == 0 || $data == '' || $data == NULL) {
		return number_format(0);
	} else {
		return number_format($data, 2, '.', ',');
	}
}

function UbahDecimal_Nol_Tiga($data)
{
	if ($data == 0 || $data == '' || $data == NULL) {
		return number_format(0);
	} else {
		return number_format($data, 3, '.', ',');
	}
}

function UbahDecimal_Nol_Tiga_Tanpa_Koma($data)
{
	if ($data == 0 || $data == '' || $data == NULL) {
		return number_format(0);
	} else {
		return number_format($data, 3, '.', ',');
	}
}

function UbahDecimal_Nol_Dua_Tanpa_Koma($data)
{
	if ($data == 0 || $data == '' || $data == NULL) {
		return number_format(0);
	} else {
		return number_format($data, 2, '.', ',');
	}
}


function PdDtl_TemporaryTerbaru()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('PdDtl_TemporaryTerbaru')) {

		$CI->db->query("
			CREATE TABLE [dbo].[PdDtl_TemporaryTerbaru](

				[NoUrut] [int] NULL,
				[NoKO] [nvarchar](15) NOT NULL,
				[Tipe] [nvarchar](100) NULL,
				[KdRAP] [nvarchar](10) NULL,
				[Uraian] [nvarchar](1000) NULL,
				[Vol] [decimal](10, 3) DEFAULT ((0)) NULL,
				[Uom] [nvarchar](15) NULL,
				[HrgSatuan] [money] DEFAULT ((0)) NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[Company] [nvarchar](10) NULL,
				[UserID] [nvarchar](30) NOT NULL,
				) ON [PRIMARY]

			");
	}
}

function PembuatanInvoiceSementara()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('Invoice_Sementara')) {
		$CI->db->query("
			CREATE TABLE [dbo].[Invoice_Sementara](
				[JobNo] [nvarchar](10) NOT NULL,
				[NoKO] [nvarchar](15) NOT NULL,
				[InvNo] [nvarchar](50) NOT NULL,
				[InvDate] [date] NULL,
				[DueDate] [date] NULL,
				[PPN] [money] NULL,
				[FPNo] [nvarchar](50) NULL,
				[FPDate] [date] NULL,
				[Total] [money] NULL,
				[Keterangan] [nvarchar](255) NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[NoSJ] [nvarchar](1000) NULL,
				[LedgerNo] [bigint] NULL DEFAULT ((0)),
				[PPH] [money] NULL,
				[TotalPayment] [money] NULL,
				[VendorNm] [nvarchar](15) NULL,
				[CentangInv] [int] NULL DEFAULT ((0)),
				[TotalTercentang] [money] NULL DEFAULT((0)),
				CONSTRAINT [PK_Invoice_Sementara] PRIMARY KEY CLUSTERED 
				(
					[JobNo] ASC,
					[NoKO] ASC,
					[InvNo] ASC
					)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]

			ALTER TABLE [dbo].[Invoice_Sementara] ADD  CONSTRAINT [DF_Invoice_Sementara_PPN]  DEFAULT ((0)) FOR [PPN]
			ALTER TABLE [dbo].[Invoice_Sementara] ADD  CONSTRAINT [DF_Invoice_Sementara_Total]  DEFAULT ((0)) FOR [Total]
			ALTER TABLE [dbo].[Invoice_Sementara] ADD  CONSTRAINT [DF_Invoice_Sementara_PPH]  DEFAULT ((0)) FOR [PPH]
			ALTER TABLE [dbo].[Invoice_Sementara] ADD  CONSTRAINT [DF_Invoice_Sementara_TotalPayment]  DEFAULT ((0)) FOR [TotalPayment]

			");
	}
}

function tbl_NewInvoice()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('NewInvoice')) {
		$CI->db->query("
			CREATE TABLE NewInvoice(
				[LedgerNo] [bigint] NULL DEFAULT ((0)),
				[NoKO] [nvarchar](15) NOT NULL,
				[VendorNm] [nvarchar](100) NULL,
				[InvNo] [nvarchar](50) NOT NULL,
				[InvDate] [date] NULL,
				[DueDate] [date] NULL,
				[Total] [money] NULL DEFAULT((0)),
				[PPN] [money] NULL DEFAULT((0)),
				[FPNo] [nvarchar](50) NULL,
				[FPDate] [date] NULL,
				[PaymentAmount] [money] NULL DEFAULT((0)),
				[TotalPayment] [money] NULL DEFAULT((0)),
				[OriginalPayment] [money] NULL DEFAULT((0)),
				[TotalTercentang] [money] NULL DEFAULT((0)),
				[IsChecked] [nvarchar](5) NULL DEFAULT((0)),
				[JobNo] [nvarchar](10) NOT NULL,
				[NoPD] [nvarchar](20) NOT NULL,
				[Company] [nvarchar](3) NOT NULL,

				)
			");
	}
}

function tgl_baru_dengan_waktu($tgl)

{
	if ($tgl == '' || $tgl == NULL || $tgl == '1900-01-01 00:00:00.000') {
		return '';
	} else {
		return date('d-M-Y H:i', strtotime($tgl));
	}
	// return date('d-M-Y', strtotime($tgl));
}

function tgl_baru($tgl)

{
	if ($tgl == '' || $tgl == NULL || $tgl == '1900-01-01 00:00:00.000') {
		return '';
	} else {
		return date('d-M-Y', strtotime($tgl));
	}
	return date('d-M-Y', strtotime($tgl));
}

function CekBuktiPendukung_Kedua($field, $value)
{
	$explode = explode(',', $field);
	$implode = implode(' ', $explode);

	$a = $implode;
	$search = strval($value);;
	if (preg_match("/{$search}/i", $a)) {
		return TRUE;
	} else {
		return FALSE;
	}
}


function ListJob($pengurutan = '')
{
	$CI = &get_instance();
	$user_id = $CI->session->userdata('MIS_LOGGED_ID');
	$Company = $CI->config->item('Company');

	$var_pengurutan = '';
	if ($pengurutan != '') {
		$var_pengurutan = 'ORDER BY ax.JobNo DESC';
	}

	$Company = $CI->config->item('Company');


	$query = $CI->db->query("

		select ax.JobNo, bx.JobNm from
		(select item as JobNo
		from
		dbo.SplitString ((select top 1 a.AksesJob from (select * from Login) as a
		left outer join
		(select * from Job) as b
		on b.JobNo = a.AksesJob
		Where a.UserID= '$user_id'), ',')) as ax
		left outer join 
		Job as bx
		on bx.JobNo = ax.JobNo WHERE bx.Company='" . $Company . "' group by ax.JobNo, bx.JobNm " . $var_pengurutan . "

		");

	return $query->result();
}

function ListAlokasi($Alokasi = '')
{
	$CI = &get_instance();
	$user_id = $CI->session->userdata('MIS_LOGGED_ID');

	$isi_where = '';

	if ($Alokasi !== '') {
		$isi_where = "WHERE ax.Alokasi='$Alokasi'";
	}

	$query = "select ax.Alokasi, bx.Keterangan, CONCAT_WS(' - ',ax.Alokasi,bx.Keterangan) as AlokasiGabung from
	(select item as Alokasi 
	from
	dbo.SplitString (
	(select top 1 a.AksesAlokasi from (select * from Login) as a
	left outer join
	(select * from Alokasi) as b
	on b.Alokasi = a.AksesAlokasi
	Where a.UserID= '$user_id')
	, ',')
	) as ax
	left outer join 
	Alokasi as bx
	on bx.Alokasi = ax.Alokasi
	" . $isi_where . "
	group by ax.Alokasi, bx.Keterangan";

	if ($Alokasi !== '') {
		$eksekusi = $CI->db->query($query)->row();
		return $eksekusi;
	} else {
		$eksekusi = $CI->db->query($query)->result();
		return $eksekusi;
	}
}

function TemukanKomaDanHapus($data)
{
	$searchForValue = ',';

	if (strpos($data, $searchForValue) !== false) {
		$data = str_replace(",", "", $data);
	}

	return $data;
}


// == DITAMBAHKAN 21 AGUSTUS 2023
function TABLE_RAP_Validasi()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('LedgerNo', 'RAP')) {
		echo 'Field Ledger tidak ada';
		die;
	}

	if (!$CI->db->field_exists('PPN', 'RAP')) {
		$CI->db->query("ALTER TABLE RAP ADD PPN money ");
	}

	// DITAMBAHKAN PADA TANGGAL 18 AGUSTUS 2023

	if (!$CI->db->field_exists('PPN_New_Persen', 'RAP')) {
		$CI->db->query("ALTER TABLE RAP ADD PPN_New_Persen int(2) NULL ");
	}

	if (!$CI->db->field_exists('PPN_New_Nominal', 'RAP')) {
		$CI->db->query("ALTER TABLE RAP ADD PPN_New_Nominal money NULL ");
	}

	if (!$CI->db->field_exists('PPN_New_Checklist', 'RAP')) {
		$CI->db->query("ALTER TABLE RAP ADD PPN_New_Checklist char(1) NULL DEFAULT((0)) ");
	}

	if (!$CI->db->field_exists('Jumlah_HrgSatuan_kali_Vol', 'RAP')) {
		$CI->db->query("ALTER TABLE RAP ADD Jumlah_HrgSatuan_kali_Vol money NULL ");
	}

	if (!$CI->db->field_exists('Total_Jumlah_Tambah_PPN_Nominal', 'RAP')) {
		$CI->db->query("ALTER TABLE RAP ADD Total_Jumlah_Tambah_PPN_Nominal money NULL ");
	}
}


function KodeAmanKhususNumeric($data)
{
	return htmlentities($data, ENT_XML1, 'UTF-8');
}

// == END DITAMBAHKAN 21 AGUSTUS 2023



// == START 25 AGUSTUS 2023 ==
function Create_RPPM_tbl()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('RPPM_tbl')) {
		$CI->db->query("
			
			CREATE TABLE [dbo].[RPPM_tbl](
				[JobNo] [nvarchar](10) NOT NULL,
				[KdRAP] [nvarchar](15) NOT NULL,
				[Versi] [nvarchar](10) NULL,
				[NoUrut] [int] NULL,
				[Uraian] [nvarchar](200) NULL,
				[Alokasi] [nvarchar](1) NOT NULL,
				[Tipe] [nvarchar](100) NULL,
				[Header] [nvarchar](100) NULL,
				[Uom] [nvarchar](15) NULL,
				[Vol] [decimal](12, 3) NULL,
				[HrgSatuan] [money] NULL,
				[HrgRAB] [money] NULL,
				[TotalTerserap] [money] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[Tahun] [nvarchar](4) NULL,
				[Jenis] [nvarchar](15) NULL,
				[LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
				[PPN] [money] NULL,
				[VolumeInputan] [decimal](12, 3) NULL,
				[Jumlah] [money] NULL,
				CONSTRAINT [PK_RPPM_tbl] PRIMARY KEY CLUSTERED 
				(
					[LedgerNo] ASC
					)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]
			
			c
			
			ALTER TABLE [dbo].[RPPM_tbl] ADD  DEFAULT ((0)) FOR [Header]

			ALTER TABLE [dbo].[RPPM_tbl] ADD  DEFAULT ((0)) FOR [Vol]
			
			ALTER TABLE [dbo].[RPPM_tbl] ADD  DEFAULT ((0)) FOR [HrgSatuan]
			
			ALTER TABLE [dbo].[RPPM_tbl] ADD  DEFAULT ((0)) FOR [HrgRAB]
			
			ALTER TABLE [dbo].[RPPM_tbl] ADD  DEFAULT ((0)) FOR [TotalTerserap]

			ALTER TABLE RPPM_tbl ADD PrdAwal date NULL 
			ALTER TABLE RPPM_tbl ADD PrdAkhir date NULL
			ALTER TABLE RPPM_tbl ADD Minggu int NULL DEFAULT((0))
			ALTER TABLE RPPM_tbl ADD Bobot_Persen money NULL DEFAULT((0))
			
			");
	}
}
// == END 25 AGUSTUS 2023 ==

// == DI TAMBAHKAN 12 SEPTEMBER 2023 ==
function PenambahanTEAMPO_TEAMPC()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('TeamPC', 'Job')) {
		$CI->db->query("ALTER TABLE Job ADD TeamPC nvarchar(50) NULL ");
	}

	if (!$CI->db->field_exists('TeamPO', 'Job')) {
		$CI->db->query("ALTER TABLE Job ADD TeamPO nvarchar(50) NULL ");
	}

	if (!$CI->db->field_exists('PIC_Project', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD PIC_Project char(1) NULL DEFAULT((0))");
	}
}

function CekNULL_KhususAngka($data)
{
	return ($data == '' || $data == NULL) ? 0 : $data;
}

function CekNULL_KhususString($data)
{
	return ($data == '' || $data == NULL) ? '' : $data;
}

function CekNULL_KhususString_Terbaru($data)
{
	return ($data == '' || $data == NULL) ? NULL : $data;
}


function tampilkan_select_Alokasi($Alokasi = '')
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi='B' OR Alokasi='C' OR Alokasi='L' OR Alokasi='E'")->result();
	$output = "";
	foreach ($query as $row) {


		$output .= '<option value="' . $row->Alokasi . '">' . $row->Alokasi . ' - ' . $row->Keterangan . '</option>';
	}
	return $output;
}

function tampilkan_select_KdRAP($Alokasi = '', $JobNo = '')
{
	$CI = &get_instance();

	$query = $CI->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC");
	$html = '<option value="">:: Pilih Salah Satu ::</option>';
	foreach ($query->result() as $row) {
		$html .= '<option value="' . $row->KdRAP . '"  >' . $row->KdRAP . ' - ' . $row->Uraian . '</option>';
	}

	return $html;
}

function numberPrecision($number, $decimals = 0)
{
	$negation = ($number < 0) ? (-1) : 1;
	$coefficient = 10 ** $decimals;
	$result = $negation * floor((string)(abs($number) * $coefficient)) / $coefficient;
	$arr = explode(".", $result);
	$num = $arr[0];
	if (empty($arr[1]))
		$num .= ".00";
	else if (strlen($arr[1]) == 1)
		$num .= "." . $arr[1] . "0";
	else
		$num .= "." . $arr[1];
	return $num;
}

function KoDtl_Field_Pembayaran()
{
	$CI = &get_instance();

	if (!$CI->db->field_exists('PeriodePengiriman1', 'KoDtl')) {
		$CI->db->query("ALTER TABLE KoDtl ADD PeriodePengiriman1 date NULL, PeriodePengiriman2 date NULL, PeriodePembayaran1 date NULL, PeriodePembayaran2 date NULL");
	}
}
function Validasi_Tgl($tgl)
{
	if ($tgl == '' || $tgl == NULL || $tgl == '1900-01-01') {
		return NULL;
	} else {
		return $tgl;
	}
}

function Add_Column_PPN_Persen()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('PPN_Persen', 'KoDtl')) {
		$CI->db->query("ALTER TABLE KoDtl ADD PPN_Persen decimal(10, 3) NULL");
	}
}


function add_Field_NoSPR_Ke_tblBarangMasuk_Header()
{
	$CI = &get_instance();

	if (!$CI->db->field_exists('NoSPR', 'tblBarangMasuk_Header')) {
		$CI->db->query("ALTER TABLE tblBarangMasuk_Header ADD NoSPR nvarchar(20) NULL ");
	}
}

function add_Field_VendorNm_Ke_tblBarangMasuk_Header()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('VendorId', 'tblBarangMasuk_Header')) {
		$CI->db->query("ALTER TABLE tblBarangMasuk_Header ADD VendorId nvarchar(7) NULL");
		$CI->db->query("ALTER TABLE tblBarangMasuk_Header ADD VendorNm nvarchar(100) NULL");
	}
}


function add_Field_Deskripsi_Ke_tblBarangMasuk_Header()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('Deskripsi', 'tblBarangMasuk_Header')) {
		$CI->db->query("ALTER TABLE tblBarangMasuk_Header ADD Deskripsi nvarchar(255) NULL");
	}
}

function BantuanExplode($data, $karakter, $nomor_yang_mau_diambil)
{
	$aksi_explode = explode($karakter, $data);
	return $aksi_explode[$nomor_yang_mau_diambil];
}

// == DISURUH PAK MUL, TAMBAH PD_PPN 23 NOVEMBER 2023
function PenambahanField_PD_PPN()
{
	$CI = &get_instance();

	if (!$CI->db->field_exists('PD_PPN', 'PdHdr')) {
		$CI->db->query("ALTER TABLE PdHdr ADD PD_PPN int NULL DEFAULT((0))");
	}
}

function CekNull_Huruf($data)
{
	return ($data == '' || $data == NULL) ? NULL : $data;
}

function CekNull_Angka_Dan_Bikin_Float($data)
{
	return ($data == '' || $data == NULL) ? 0 : (float) $data;
}

function CekNull_Untuk_Tanggal($data)
{
	return ($data == '' || $data == NULL || $data == '1900-01-01') ? NULL : $data;
}

function PembuatanTable_PdDtl_Temp_Keuangan()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('PdDtl_Temp_Keuangan')) {
		$CI->db->query("
			CREATE TABLE [PdDtl_Temp_Keuangan](
				[NoPD] [nvarchar](20) NULL ,
				[NoUrut] [int] NULL,
				[Tipe] [nvarchar](100) NULL,
				[KdRAP] [nvarchar](10) NULL,
				[Uraian] [nvarchar](800) NULL,
				[Vol] [decimal](10, 3) NULL DEFAULT ((0)),
				[Uom] [nvarchar](15) NULL,
				[HrgSatuan] [money] NULL DEFAULT ((0)),
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[NoPJ] [nvarchar](20) NULL,
				[PjUraian] [nvarchar](2000) NULL,
				[PjVol] [decimal](10, 3) NULL DEFAULT ((0)),
				[PjHrgSatuan] [money] NULL DEFAULT ((0)),
				[PjUserEntry] [nvarchar](30) NULL,
				[PjTimeEntry] [datetime] NULL,
				[NoKO] [nvarchar](15) NOT NULL,
				[Company] [nvarchar](3) NULL,
				[JobNo] [nvarchar](15) NOT NULL,

				) ON [PRIMARY]
			");
	}
}

function PembuatanTable_Invoice_Temp_Keuangan()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('Invoice_Temp_Keuangan')) {
		$CI->db->query("
			CREATE TABLE [Invoice_Temp_Keuangan](
				[NoKO] [nvarchar](15) NOT NULL,
				[VendorNm] [nvarchar](100) NULL,
				[InvNo] [nvarchar](50) NOT NULL,
				[InvDate] [date] NULL,
				[DueDate] [date] NULL,
				[Total] [money] NULL DEFAULT ((0)),
				[PPN] [money] NULL DEFAULT ((0)),
				[FPNo] [nvarchar](50) NULL,
				[FPDate] [date] NULL,
				[PaymentAmount] [money] NULL DEFAULT ((0)),
				[TotalPayment] [money] NULL DEFAULT ((0)),
				[OriginalPayment] [money] NULL DEFAULT ((0)),
				[IsChecked] [int] NULL DEFAULT ((0)),
				[JobNo] [nvarchar](10) NOT NULL,
				[NoPD] [nvarchar](20) NULL ,
				[Company] [nvarchar](3) NULL,
				)
			");
	}
}

function PenambahanField_InputDari()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('InputDari', 'PdHdr')) {
		$CI->db->query("ALTER TABLE PdHdr ADD InputDari char(1) NULL");
	}
}

function PenambahanField_TglPengakuan_Di_RPPM_tbl()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('TglPengakuan', 'RPPM_tbl')) {
		$CI->db->query("ALTER TABLE RPPM_tbl ADD TglPengakuan date NULL");
	}
}

function PembuatanTable_tbReport_GraphCurva_YearOfMonth()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('tbReport_GraphCurva_YearOfMonth')) {
		$CI->db->query("
			CREATE TABLE [tbReport_GraphCurva_YearOfMonth](
				[RencanaK] [decimal](18, 2) NULL,
				[bulan] [varchar](2) NULL,
				[tahun] [char](4) NULL,
				[Tanggal_Gabungan] [varchar](8) NULL,
				[JobNo] [varchar](6) NULL,
				[Tanggal_Rencana_Termin] [datetime] NULL,
				[Persentase_Termin] [decimal](18, 0) NULL,
				[Ballance_R_Termin1] [decimal](18, 0) NULL,
				[KSO] [int] NULL,
				[RencanaK2] [decimal](18, 3) NULL,
				[RealisasiK] [decimal](18, 3) NULL,
				[balance_rencana_progress] [decimal](18, 0) NULL,
				[balance_realisasi_progress] [decimal](18, 0) NULL,
				[Rencana_Termin] [decimal](18, 0) NULL,
				[balance_rencana_termin] [decimal](18, 0) NULL,
				[Realisasi_Termin] [decimal](18, 0) NULL,
				[balance_realisasi_termin] [decimal](18, 0) NULL,
				[balance_rencana_biaya_rap] [decimal](18, 0) NULL,
				[balance_realisasi_biaya_rap] [decimal](18, 0) NULL,
				[Bobot_Persen] [decimal](18, 3) NULL,
				[balance_rppm] [decimal](18, 3) NULL
				) ON [PRIMARY]
			");
	}
}

function PembuatanTable_RAP_Cadangan()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('RAP_Cadangan')) {
		$CI->db->query("
			CREATE TABLE [RAP_Cadangan](
				[JobNo] [nvarchar](10) NOT NULL,
				[KdRAP] [nvarchar](15) NOT NULL,
				[Versi] [nvarchar](10) NULL,
				[NoUrut] [int] NULL,
				[Uraian] [nvarchar](200) NULL,
				[Alokasi] [nvarchar](1) NOT NULL,
				[Tipe] [nvarchar](100) NULL,
				[Header] [nvarchar](100) NULL,
				[Uom] [nvarchar](15) NULL,
				[Vol] [decimal](12, 3) NULL,
				[HrgSatuan] [money] NULL,
				[HrgRAB] [money] NULL,
				[TotalTerserap] [money] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[Tahun] [nvarchar](4) NULL,
				[Jenis] [nvarchar](15) NULL,
				[LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
				[PPN] [money] NULL,
				[PPN_New_Persen] [char](2) NULL,
				[PPN_New_Nominal] [money] NULL,
				[PPN_New_Checklist] [char](1) NULL,
				[Jumlah_HrgSatuan_kali_Vol] [money] NULL,
				[Total_Jumlah_Tambah_PPN_Nominal] [money] NULL,
				CONSTRAINT [PK_RAP_Cadangan_1] PRIMARY KEY CLUSTERED 
				(
					[JobNo] ASC,
					[KdRAP] ASC,
					[Alokasi] ASC
					)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]
			ALTER TABLE [RAP_Cadangan] ADD  CONSTRAINT [DF_RAP_Cadangan_NoUrut]  DEFAULT ((0)) FOR [NoUrut]
			ALTER TABLE [dbo].[RAP_Cadangan] ADD  CONSTRAINT [DF_RAP_Cadangan_Header]  DEFAULT ((0)) FOR [Header]
			ALTER TABLE [RAP_Cadangan] ADD  CONSTRAINT [DF_RAP_Cadangan_vol]  DEFAULT ((0)) FOR [Vol]
			ALTER TABLE [RAP_Cadangan] ADD  CONSTRAINT [DF_RAP_Cadangan_HrgSatuan]  DEFAULT ((0)) FOR [HrgSatuan]
			ALTER TABLE [RAP_Cadangan] ADD  CONSTRAINT [DF_RAP_Cadangan_HrgRAB]  DEFAULT ((0)) FOR [HrgRAB]
			ALTER TABLE [RAP_Cadangan] ADD  CONSTRAINT [DF_RAP_Cadangan_TotalTerserap]  DEFAULT ((0)) FOR [TotalTerserap]
			ALTER TABLE [RAP_Cadangan] ADD  DEFAULT ((0)) FOR [PPN_New_Checklist]

			");
	}
}

function PembuatanField_TotalHargaKontrak_Satuan()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('TotalHargaKontrak_Satuan', 'RPPM_tbl')) {
		$CI->db->query("ALTER TABLE RPPM_tbl ADD TotalHargaKontrak money NULL ");
		$CI->db->query("ALTER TABLE RPPM_tbl ADD TotalHargaKontrak_Satuan money NULL ");
		$CI->db->query("ALTER TABLE RPPM_tbl ADD JumlahHarga_Satuan money NULL ");
	}
}

function UangRound_Dan_NumberFormat($Uang)
{
	$Uang = round($Uang);
	return number_format($Uang);
}

// -- RPPM History di tambahkan pada : 12 DES 2023
function PenambahanField_RPPMHistory_To_Login()
{
	$CI = &get_instance();

	if (!$CI->db->field_exists('RPPMHistory', 'Login')) {
		$CI->db->query("ALTER TABLE Login ADD RPPMHistory char(1) NULL DEFAULT((0)) ");
	}
}

function PembuatanTable_PJCadangan()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('PJCadangan')) {
		$CI->db->query("
			
			CREATE TABLE [dbo].[PJCadangan](
				[NoPD] [nvarchar](20) NOT NULL,
				[NoUrut] [int] NULL,
				[KdRAP] [nvarchar](10) NULL,
				[Uraian] [nvarchar](800) NULL,
				[Vol] [decimal](10, 3) NULL,
				[Uom] [nvarchar](15) NULL,
				[HrgSatuan] [money] NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[NoPJ] [nvarchar](20) NULL,
				[PjUraian] [nvarchar](2000) NULL,
				[PjVol] [decimal](10, 3) NULL,
				[PjHrgSatuan] [money] NULL,
				[PjUserEntry] [nvarchar](30) NULL,
				[PjTimeEntry] [datetime] NULL,
				[Company] [nvarchar](10) NULL,
				[NewRecord] char(3) NULL
				) ON [PRIMARY]
			
			ALTER TABLE [dbo].[PJCadangan] ADD  CONSTRAINT [DF__PJCadangan__Vol__4B8221F7]  DEFAULT ((0)) FOR [Vol]
			
			ALTER TABLE [dbo].[PJCadangan] ADD  CONSTRAINT [DF__PJCadangan__HrgSatuan__4C764630]  DEFAULT ((0)) FOR [HrgSatuan]
			
			ALTER TABLE [dbo].[PJCadangan] ADD  CONSTRAINT [DF__PJCadangan__PjVol__4D6A6A69]  DEFAULT ((0)) FOR [PjVol]
			
			ALTER TABLE [dbo].[PJCadangan] ADD  CONSTRAINT [DF__PJCadangan__PjHrgSatu__4E5E8EA2]  DEFAULT ((0)) FOR [PjHrgSatuan]
			

			");
	}
}

function PembuatanField_BobotPersenNEW()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('PembuatanField_BobotPersenNEW', 'RPPM_tbl')) {
		$CI->db->query("ALTER TABLE RPPM_tbl ADD PembuatanField_BobotPersenNEW decimal(12,5) NULL ");
	}
}

function jadikan_json($datasaya)
{
	$explode = explode(',', $datasaya);
	return json_encode($explode);
	// $NoSJ_DB = json_encode($pecah);
}

function Pembuatan_InvNo($nama_table = '')
{
	$CI = &get_instance();
	if ($nama_table == '') {
	} else {
		if (!$CI->db->field_exists('InvNo', $nama_table)) {
			$CI->db->query("ALTER TABLE " . $nama_table . " ADD InvNo nvarchar(50) NULL ");
		}
	}
}


function Create_FieldBaru_TotalNominalPPN()
{
	$CI = &get_instance();

	if (!$CI->db->field_exists('TotalNominalPPN', 'KoDtl')) {
		$CI->db->query("ALTER TABLE KoDtl ADD TotalNominalPPN money NULL");
	}
}


function PembuatanField_Persetujuan_TagihanLebihBesar()
{
	$CI = &get_instance();

	if (!$CI->db->field_exists('Ijinkan_TagihanLebihBesar', 'Invoice')) {
		$CI->db->query("ALTER TABLE Invoice ADD Ijinkan_TagihanLebihBesar char(1) NULL DEFAULT((0))");
	}
}

function PembuatanTable_InvoiceSJ()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('Invoice_SJ')) {
		$CI->db->query("
			
			CREATE TABLE Invoice_SJ(
				[NoKO] [nvarchar](15) NULL,
				[NoSJ] [nvarchar](20) NULL,
				[InvNo] [nvarchar](50) NULL,
				[JobNo] [nvarchar](10) NULL,
				[NoUrut] int DEFAULT ((0)) NOT NULL,
				[KdRAP] nvarchar(10) NULL,
				[Uraian] nvarchar(255)  NULL,
				[Uom] nvarchar(15) NULL,
				[VolBrgMasuk] decimal(10,3) DEFAULT ((0)) NULL,
				[HrgSatuan] money DEFAULT ((0)) NULL,
				[UserEntry] nvarchar(30)  NULL,
				[TimeEntry] datetime  NULL,
				[SisaInvoice] char(1) DEFAULT ((0)) NULL,
				)
			");
	}
}


function AddField_SisaInvoice_To_tblBarangMasuk_Detail()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('SisaInvoice', 'tblBarangMasuk_Detail')) {
		$CI->db->query("ALTER TABLE tblBarangMasuk_Detail ADD SisaInvoice char(1) NULL DEFAULT((0))");
	}
}

function AddField_SisaInvoice_To_Invoice_SJ()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('SisaInvoice', 'Invoice_SJ')) {
		$CI->db->query("ALTER TABLE Invoice_SJ ADD SisaInvoice char(1) NULL DEFAULT((0))");
	}
}

function AddField_VersiRAP_ToTable_KoDtl()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('VersiRAP', 'KoDtl')) {
		$CI->db->query("ALTER TABLE KoDtl ADD VersiRAP [nvarchar](10) NULL");
	}
}

function AddField_VersiRAP_ToTable_PRDtl()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('VersiRAP', 'PRDtl')) {
		$CI->db->query("ALTER TABLE PRDtl ADD VersiRAP [nvarchar](10) NULL");
	}
}

function AddField_VersiRAP_ToTable_PdDtl()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('VersiRAP', 'PdDtl')) {
		$CI->db->query("ALTER TABLE PdDtl ADD VersiRAP [nvarchar](10) NULL");
	}
}

function PengecekanVersiRAP($KdRAP, $JobNo, $Alokasi)
{
	$CI = &get_instance();

	$query = $CI->db->query("SELECT ISNULL(Versi,NULL) AS Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$KdRAP'  ");
	if ($query->num_rows() > 0) {
		$row = $query->row();
		if ($row->Versi == '' || $row->Versi == NULL) {
			return NULL;
		} else {
			return $row->Versi;
		}
	}
}

function PembuatanTableDaftarMaterial()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('tbl_DaftarMaterial')) {
		$CI->db->query("
			CREATE TABLE [dbo].[tbl_DaftarMaterial](
				[KdBarang] [varchar](10) NULL,
				[NmBarang] [varchar](255) NULL,
				[Kategori] [varchar](50) NULL,
				[SubKategori] [varchar](50) NULL,
				[TimeEntry] [datetime] NULL,
				[ID] [int] IDENTITY(1,1) NOT NULL,
				PRIMARY KEY CLUSTERED 
				(
					[ID] ASC
					)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]
			");
	}
}

function CreateTableViewPelamar()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('ViewPelamar')) {
		$CI->db->query("CREATE TABLE ViewPelamar(LedgerNo INT PRIMARY KEY IDENTITY(1,1),
			PelamarID VARCHAR(10),
			Voters VARCHAR(255),)");
	}
}

if (!function_exists('PenghindarXss')) {

	function PenghindarXss($var, $double_encode = TRUE)
	{
		if (empty($var)) {
			return $var;
		}

		if (is_array($var)) {
			foreach (array_keys($var) as $key) {
				$var[$key] = html_escape($var[$key], $double_encode);
			}

			return $var;
		}

		return htmlspecialchars($var, ENT_QUOTES, config_item('charset'), $double_encode);
	}
}

function HapusKutip_DuaDanSatu($str)
{
	return trim($str, '"');
}

function HapusTitikDiAkhirString($str)
{
	return rtrim($str, '.');
}


function konversi_BR_ke_enter($value)
{
	return str_replace("\r\n", "<br />", $value);
}

function url_cv_pelamar_hris($type = '')
{
	$direktori = NULL;
	if ($type == '' || $type == 'local') {
		$direktori = 'http://127.0.0.1/HR_INTEGRASI/images/pelamar/cv/';
	} else {
		$direktori = 'http://147.139.178.49/HRIS_ver1/images/pelamar/cv/';
	}
	return $direktori;
}


function konversi_BR_ke_enter_ada_htmlentitynya($value)
{
	$value = htmlentities($value);
	return str_replace("\r\n", "<br />", $value);
}

function AddField_lastVote_dilihat_To_ViewPelamar()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('lastVote', 'tblPelamarr')) {
		$CI->db->query("ALTER TABLE tblPelamarr ADD lastVote varchar(255), dilihat varchar(5) ");
	}
}

function CreateTablePermintaanKaryawan()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('PermintaanKaryawan')) {
		$CI->db->query("CREATE TABLE PermintaanKaryawan (
			IDRequest varchar(10) NOT NULL,
			Posisi varchar(50) NOT NULL,
			Jumlah INT NOT NULL,
			Penempatan varchar(50) NOT NULL,
			TglSiapKerja varchar(10),
			DurasiKontrak varchar(20),
			Kualifikasi varchar(255),
			Status varchar(10)
		)");
	}
}

function pembuatantable_KoDtl_subrow()
{
	$CI = &get_instance();

	if (!$CI->db->table_exists('KoDtl_subrow')) {
		$CI->db->query("

			CREATE TABLE KoDtl_subrow(
				NoKO nvarchar(15) NULL,
				NoUrut_subrow int DEFAULT ((0)) NULL,
				KdRAP_subrow nvarchar(10) NULL,
				NoUrut_KdRAP int DEFAULT ((0)) NULL,
				Uom_subrow nvarchar(15) NULL,
				Vol_subrow decimal(10, 3) NULL,
				HrgSatuan_subrow money DEFAULT ((0)) NULL,
				JumlahHarga_subrow money DEFAULT ((0)) NULL, 
				PeriodePengiriman1 date NULL,
				PeriodePengiriman2 date NULL,
				PeriodePembayaran1 date NULL,
				PeriodePembayaran2 date NULL,
				UserEntry nvarchar(30) NULL,
				TimeEntry datetime NULL,
				Company nvarchar(3) NULL,
				)
			
			");
	}

}

function CreateTableForumDiskusi()
{
	$CI = &get_instance();
	if (!$CI->db->table_exists('tblForumDiskusi')) {
		$CI->db->query("CREATE TABLE tblForumDiskusi (
			Id INT PRIMARY KEY IDENTITY(1,1),
			IDThread varchar(10) NOT NULL,
			Thread TEXT NOT NULL,
			DatePost varchar(50) NOT NULL,
			CreatedBy varchar(50) NOT NULL,
			UserName varchar(50) NOT NULL,
			IsActive INT
		)");
	}
	if (!$CI->db->table_exists('tblCommentDiskusi')) {
		$CI->db->query("CREATE TABLE tblCommentDiskusi(
			IDComment INT PRIMARY KEY IDENTITY(1,1),
			IDThread VARCHAR(10),
			IdUser VARCHAR(50),
			UserName VARCHAR(50),
			ValueComment TEXT,
			DateCreated VARCHAR(20),
			ReplyOn VARCHAR(5)
		)");
	}
}

function AddFieldInvReceiveDateToTableInvoice()
{
	$CI = &get_instance();
	if (!$CI->db->field_exists('InvReceiveDate', 'Invoice')) {
		$CI->db->query("ALTER TABLE Invoice ADD InvReceiveDate date NULL");
	}
}

function CekWarna($nilai_awal)
	{
		if ($nilai_awal == 0) {
			return 'bg-yellow-active';
		}

		if ($nilai_awal > 0) {
			return 'bg-green';
		}

		if ($nilai_awal < 0) {
			return 'bg-orange';
		}
	}

	function koma2($data)
{
	$bilangan = sprintf("%1\$.2f", $data);
	return $bilangan;
}

