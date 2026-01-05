<?php

function KodeUrutPd($JobNo = '', $Alokasi = '', $PD_Dari='')
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

	}else if($PD_Dari == 'MIX'){

		$kode_statik = 'PDMIX';
		$NamaCounter = 'CounterPDMIX';

	}else{

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

	function OtomatisUbahCounter($JobNo, $Alokasi, $status_kode,$status_tambah='')
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

			if ($status_tambah == 'RKD' ) {
				$nama_counter = 'CounterKSO';
			}else if ($status_tambah == 'MIX'){
				$nama_counter = 'CounterPDMIX';	
			}else{
				$nama_counter = 'CounterPD';
			}

			$data_counter[$nama_counter] = 1;
			$CI->db->insert('Counter', $data_counter);
        // return 'ok';

		} else {

			$nama_counter = '';
			if ($status_tambah == 'RKD' ) {
				$nama_counter = 'CounterKSO';
			}else if ($status_tambah == 'MIX'){
				$nama_counter = 'CounterPDMIX';	
			}else{
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

	function dapatkan_foto_karyawan_dari_sistem_lain($NIK,$pribadi=''){
		$CI =& get_instance();

		// $FotoNama = 'assets/foto_karyawan/user.jpg';
		$FotoNama = 'assets/foto_karyawan/user.jpg';
		$where_cek = NULL;
		if($pribadi !='' OR $pribadi !=NULL  ){
			$where_cek = " AND StatusFoto='Pribadi' ";
		}


		$cek_foto = $CI->db->query("SELECT TOP 1 * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND TipeFile='Foto'  ".$where_cek." ");



		if ($cek_foto->num_rows() > 0 ) {
			$row = $cek_foto->row_array();
			if ($row['NamaFile'] != NULL || $row['NamaFile'] != ''   ) {
				$FotoNama = 'assets/foto_karyawan/'.$row['NamaFile'];
			}
			
		}else{
			$FotoNama = 'assets/foto_karyawan/user.jpg';
		}

		return  $FotoNama;
	}


	function dapatkan_foto_karyawan($NIK){
		$CI =& get_instance();

		$FotoNama = 'assets/foto_karyawan/user.jpg';
		$cek_foto = $CI->db->query("SELECT TOP 1 * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND TipeFile='Foto'  ");
		if ($cek_foto->num_rows() > 0 ) {
			$row = $cek_foto->row_array();
			$FotoNama = 'assets/foto_karyawan/'.$row['NamaFile'];

		}

		return $FotoNama;
	}
	
	function dapatkan_foto_karyawan_baru($NIK,$pribadi=''){
		$CI =& get_instance();

		// $Company = 'KIP';

		$FotoNama = 'assets/foto_karyawan/user.jpg';
		
		$where_cek = NULL;
		if($pribadi !='' OR $pribadi !=NULL  ){
			$where_cek = " AND StatusFoto IS NOT NULL OR StatusFoto !='' ";
		}
		

		$cek_foto = $CI->db->query("SELECT TOP 1 * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND TipeFile='Foto' ".$where_cek	."  ");
		if ($cek_foto->num_rows() > 0 ) {
			$row = $cek_foto->row_array();
			$FotoNama = 'assets/foto_karyawan/'.$row['NamaFile'];

		}

		if ($CI->config->item('Company') != 'MDH') {
			$FotoNama = 'assets/foto_karyawan/Tomy_Bani_Adam_221123111119_Foto.PNG';
		}

		return $FotoNama;
	}


	function CekAkses_dan_field_pada_database(){
		$CI =& get_instance();

		if (!$CI->db->field_exists('Akses_DataProyek', 'Login'))
		{
			$CI->db->query("ALTER TABLE Login ADD Akses_DataProyek char(1) NULL DEFAULT((0))");
		}

		if (!$CI->db->field_exists('Akses_DataKontrak', 'Login'))
		{
			$CI->db->query("ALTER TABLE Login ADD Akses_DataKontrak char(1) NULL DEFAULT((0))");
		}

		if (!$CI->db->field_exists('Akses_TataKelola', 'Login'))
		{
			$CI->db->query("ALTER TABLE Login ADD Akses_TataKelola char(1) NULL DEFAULT((0))");
		}

		if (!$CI->db->field_exists('Akses_LeafletProyek', 'Login'))
		{
			$CI->db->query("ALTER TABLE Login ADD Akses_LeafletProyek char(1) NULL DEFAULT((0))");
		}

		if (!$CI->db->field_exists('Akses_MOS_In_Out', 'Login'))
		{
			$CI->db->query("ALTER TABLE Login ADD Akses_MOS_In_Out char(1) NULL DEFAULT((0))");
		}

		if (!$CI->db->field_exists('Akses_MOS_list', 'Login'))
		{
			$CI->db->query("ALTER TABLE Login ADD Akses_MOS_list char(1) NULL DEFAULT((0))");
		}

		if (!$CI->db->field_exists('Akses_ApprovalMOS', 'Login'))
		{
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



	// ----------------------------------------------------------------------------------------------------------
	function pembuatan_tbl_InvPD_sementara(){
		$CI =& get_instance();
		if (!$CI->db->table_exists('InvPD_sementara') )
		{
			$CI->db->query("
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

	function pembuatan_tbl_invoice_sementara(){
		$CI =& get_instance();
		if (!$CI->db->table_exists('tbl_invoice_sementara') )
		{

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

	function tambah_ledgerNo_di_InvPD_sementara(){
		$CI =& get_instance();
		if (!$CI->db->field_exists('LedgerNo', 'InvPD_sementara'))
		{
			$CI->db->query("ALTER TABLE InvPD_sementara ADD LedgerNo bigint NULL");
		}
	}

	function tambah_ledgerNo_di_InvPD(){
		$CI =& get_instance();
		if (!$CI->db->field_exists('LedgerNo', 'InvPD'))
		{
			$CI->db->query("ALTER TABLE InvPD ADD  LedgerNo BigInt identity");
		}
	}

	function pembuatan_tbl_PdDtl_sementara(){
		$CI =& get_instance();
		if (!$CI->db->table_exists('PdDtl_sementara') )
		{
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

	function uang_koma_genap_decimal($uang=0){
		$CI =& get_instance();
		return number_format((float)$uang, 0, '.', ',');
	}
	function uang_koma_genap_decimal_kedua($nilai)
	{	
		$nilai = round($nilai,2);
		return number_format($nilai,2); 
	}


	function cek_status_PD_untuk_LAPANGAN($LedgerNo=''){
		if ($LedgerNo=='') {
			return '';
		}

		$CI =& get_instance();
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

	function job_data($JobNo){
		$CI =& get_instance();
		$query =  $CI->db->query(" SELECT JobNo, JobNm, Lokasi FROM Job WHERE JobNo='$JobNo' ")->row();
		return $query;
	}

	function cek_pj_saya($NoPJ=null, $data_asli){
		return ($NoPJ == '' || $NoPJ == NULL) ? '' : $data_asli;
	}

	function pembuatan_field_logo_baru(){
		$CI =& get_instance();
		if (!$CI->db->field_exists('Logo_baru', 'Job'))
		{
			$CI->db->query("ALTER TABLE Job ADD Logo_baru ntext NULL ");
		}
	}

	function update_logo_company($JobNo=''){
		if ($JobNo == '' || $JobNo == NULL ) {
			exit;
		}

		$CI =& get_instance();

		$q = $CI->db->select("Logo_baru")->where('JobNo',$JobNo)->get('Job')->row_array();
		if ($q['Logo_baru'] != NULL || $q['Logo_baru'] != NULL  ) {
			
			if (file_exists('assets/doc/'.$q['Logo_baru'])) {
				unlink('assets/doc/'.$q['Logo_baru']);
			}

		}
		
	}

	function tambah_field_password_di_tbl_karyawan(){
		$CI =& get_instance();
		if (!$CI->db->field_exists('Password_dari_CI', 'Karyawan'))
		{
			$CI->db->query("ALTER TABLE Karyawan ADD Password_dari_CI ntext NULL");
		}

		if (!$CI->db->field_exists('Password_dari_CI', 'Login'))
		{
			$CI->db->query("ALTER TABLE Login ADD Password_dari_CI ntext NULL");
		}


	}
	


	function alamat_server_dan_local_dan_folder($type=''){
		// jika kosong maka tipenya jadi localhost, jika tidak maka server
		$myurl = NULL;
		if ($type == '' || $type == 'local') {
			$myurl = 'http://127.0.0.1/MDH_07_Des2022/';

			//utk di sistem pak mul
			$myurl = 'http://127.0.0.1:8081/MDH_07_Des2022/';

		}else{
			$myurl = 'http://147.139.178.49/mdh_ver1.1/';
		}
		return $myurl;
	}

	function url_utk_document_root_dan_nama_folder($type=''){

		$direktori = NULL;
		if ($type == '' || $type == 'local') {
			$direktori = $_SERVER['DOCUMENT_ROOT'].'/MDH_07_Des2022/assets/foto_karyawan/';
		}else{
			$direktori = $_SERVER['DOCUMENT_ROOT'].'/mdh_ver1.1/assets/foto_karyawan/';
		}
		return $direktori;

	}
	function hapus_file_untuk_document_root($type='',$NamaFile=''){

		$akses_folder = NULL;



		if ($type == '' || $type == 'local') {
			if ($NamaFile == '') {
				$akses_folder = $_SERVER['DOCUMENT_ROOT'].'/MDH_07_Des2022/assets/dokumen_pendukung_karyawan/';
			}else{
				$akses_folder = $_SERVER['DOCUMENT_ROOT'].'/MDH_07_Des2022/assets/dokumen_pendukung_karyawan/'.$NamaFile;
			}


		}else{

			if ($NamaFile == '') {
				$akses_folder = $_SERVER['DOCUMENT_ROOT'].'/mdh_ver1.1/assets/dokumen_pendukung_karyawan/';
			}else{
				$akses_folder = $_SERVER['DOCUMENT_ROOT'].'/mdh_ver1.1/assets/dokumen_pendukung_karyawan/'.$NamaFile;
			}

		}
		return $akses_folder;

	}

