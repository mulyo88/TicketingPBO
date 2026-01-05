	<?php
	defined('BASEPATH') or exit('No direct script access allowed');

	class Welcome extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model('dashboardmod');
			$this->load->helper('racode');
			is_login();

			// if (!$this->db->field_exists('DataTerlambat', 'Login')) {
			// 	$this->db->query("ALTER TABLE Login ADD DataTerlambat char(1)");
			// }

			// $this->output->enable_profiler(true);
		}

		private function ListJob_New()
		{
			$Company = $this->config->item('Company');

			$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
			// $AksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='liasw' ")->row()->AksesJob;
			$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$Company' ");

			$ArrayJob = array();
			foreach ($QueryJob->result_array() as $myjob):
				// 
				if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
					continue;
				}
				$ArrayJob[] = array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
			// echo $myjob['JobNo'].'<br>';
			endforeach;

			// print_rr($ArrayJob);
			return $ArrayJob;
		}


		public function index()
		{


			error_reporting(0);
			// $data['ListJob_New'] = $this->ListJob_New();
			// // print_rr($this->ListJob_New());
			// // exit;

			// $data['bruto'] = '';
			// $data['RAP_A'] = 0;
			// $data['RAP_B'] = 0;
			// $data['RAP_C'] = 0;
			// $data['biaya_B'] = '';
			// $data['biaya_C'] = '';
			// $data['sisa_hutang_KO'] = '';
			// $data['sum_total_YAD_B'] = '';
			// $data['sum_total_YAD_C'] = '';
			// $data['hitung_1_2_3'] = '';
			// $data['sisa_kontrak'] = '';
			// $data['sisa_RAP'] = '';
			// $data['JobNoChart'] = '';


			// //data baru
			// $data['total_RAP'] = 0;
			// $data['gross_profit_kontrak'] = 0;
			// $data['total_realisasi_biaya'] = 0;
			// $data['total_YAD'] = 0;
			// $data['persen_total_RAP'] = 0;

			// //berakhir data baru

			// $persen_rap_a = 0;
			// $persen_rap_b = 0;
			// $persen_rap_c = 0;
			// $persen_biaya = 0;
			// $persen_sisa_kontrak = 0;
			// $persen_sisa_RAP = 0;


			// //data termin diterima
			// $data['total_termin_diterima'] = 0;
			// $total_termin_diterima = 0;
			// $sisa_biaya_terhadap_termin = 0;
			// $sisa_rap_terhadap_termin = 0;

			// $total_biaya_baru = 0;
			// $persen_biaya_baru = 0;
			// $sisa_biaya_terhadap_kontrak_baru = 0;
			// $persen_sisa_biaya_terhadap_kontrak_baru = 0;
			// $sisa_biaya_rap_terhadap_kontrak = 0;
			// $persen_sisa_biaya_rap_terhadap_kontrak = 0;

			// //berakhir data termin diterima



			// //awal data terhadap sisa termin
			// $sisa_penerimaan_termin_saat_ini = 0;
			// $total_biaya_penerimaan_termin_saat_ini = 0;
			// $sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini = 0;
			// $sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini = 0;
			// //akhir data terhadap sisa termin


			// //progress keuangan vs fisik
			// $data['total_progress_keuangan'] = 0;
			// $data['total_progress_keuangan'] = 0;

			// //berakhir progress keuangan vs fisik


			// if (isset($_POST['JobNoChart'])) {
			// 	$JobNo = $this->input->post('JobNoChart');
			// 	$data['JobNoChart'] = $JobNo;

			// 	$query_kontrak = "SELECT * FROM Job WHERE JobNo='$JobNo' ";
			// 	$ekse_kontrak = $this->db->query($query_kontrak)->row();

			// 	$query_RAP_A = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='A' AND Tipe !='Header' ";
			// 	$ekse_RAP_A = $this->db->query($query_RAP_A);
			// 	$query_RAP_B = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='B' AND Tipe !='Header' ";
			// 	$ekse_RAP_B = $this->db->query($query_RAP_B);

			// 	$query_RAP_C = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='C' AND Tipe !='Header' ";
			// 	$ekse_RAP_C = $this->db->query($query_RAP_C);

			// 	$query_biaya = "SELECT Alokasi, ISNULL(SUM(Amount),0) AS 'TtlBLE' FROM BLE WHERE JobNo='$JobNo'  GROUP BY Alokasi";
			// 	$ekse_biaya = $this->db->query($query_biaya);

			// 	$query_biaya_KO = "SELECT A.Jobno AS JobNo, D.JobNm, A.NoKO, A.TglKO, A.KategoriId, A.VendorId, B.VendorNm, A.SubTotal-A.DiscAmount+A.PPN AS TotalKO, D.Company,
			// ISNULL((SELECT SUM(Amount) FROM BLE WHERE NoKO=A.NoKO),0) AS PaymentKO,
			// (CASE
			// 	WHEN A.ClosedBy IS NULL THEN
			// 	A.SubTotal-A.DiscAmount+A.PPN - ISNULL((SELECT SUM(AMOUNT) FROM BLE WHERE NoKO=A.NoKO),0)
			// 	ELSE
			// 	'0'
			// 	END) as 'RemainingKO'
			// FROM KoHdr A
			// LEFT JOIN Vendor B ON B.VendorId=A.VendorId
			// LEFT JOIN Job D  ON D.JobNo=A.JobNo
			// WHERE A.JobNo='$JobNo'  AND A.ApprovedBy IS NOT NULL AND A.CanceledBy IS NULL";
			// 	$ekse_biaya_KO = $this->db->query($query_biaya_KO);
			// 	$query_YAD_B = "SELECT * FROM YAD WHERE JobNo='$JobNo' AND Alokasi='B' ";
			// 	$ekse_YAD_B = $this->db->query($query_YAD_B);

			// 	$query_YAD_C = "SELECT * FROM YAD WHERE JobNo='$JobNo' AND Alokasi='C' ";
			// 	$ekse_YAD_C = $this->db->query($query_YAD_C);


			// 	$query_progress_fisik = "SELECT TOP 1 * FROM Progress WHERE JobNo='$JobNo' ORDER BY TimeEntry DESC";
			// 	$ekse_progress_fisik = $this->db->query($query_progress_fisik);

			// 	$query_totalAsset = "Select SUM(SubTotalHarga) AS Total FROM DataAsset ";
			// 	$ekse_query_totalAsset = $this->db->query($query_totalAsset);


			// 	$Total_Asset = 0;
			// 	foreach ($ekse_query_totalAsset->result() as $Q_TotalAsset) {
			// 	}

			// 	$bruto = 0;
			// 	if ($ekse_kontrak->KSO == 1) {

			// 		if ($ekse_kontrak->Own == 1) {
			// 			$bruto = ($ekse_kontrak->Bruto * $ekse_kontrak->PersenShare1) / 100;
			// 		} else {
			// 			$bruto = ($ekse_kontrak->Bruto * $ekse_kontrak->PersenShare2) / 100;
			// 		}

			// 		if (CariKataLoan($ekse_kontrak->SumberDana) != 'LOAN') {
			// 			$hitung_netto1 = $bruto / 1.1;
			// 			$hitung_netto = $hitung_netto1 * 0.97;
			// 			// echo 'ok';
			// 		} else {
			// 			$hitung_netto = $bruto;
			// 			// echo 'no';
			// 		}


			// 		// echo CariKataLoan($ekse_kontrak->SumberDana);
			// 		// exit;

			// 	} else {

			// 		// if (CariKataLoan($ekse_kontrak->SumberDana) == 'LOAN') {
			// 		// 	$hitung_netto1 = $bruto / 1.1;
			// 		// 	$hitung_netto = $hitung_netto1 * 0.97;
			// 		// } else {
			// 		// 	$hitung_netto = $bruto;
			// 		// }

			// 		$bruto = $ekse_kontrak->Netto;
			// 		$hitung_netto = $bruto;
			// 	}

			// 	$sum_total_A = 0;
			// 	$persen_rap_a = 0;
			// 	foreach ($ekse_RAP_A->result() as $query_RAP_A) {
			// 		$sum_total_A += $query_RAP_A->Total;
			// 	}

			// 	$sum_total_B = 0;
			// 	$persen_rap_b = 0;
			// 	foreach ($ekse_RAP_B->result() as $query_RAP_B) {
			// 		$sum_total_B += $query_RAP_B->Total;
			// 	}

			// 	$sum_total_C = 0;
			// 	foreach ($ekse_RAP_C->result() as $query_RAP_C) {
			// 		$sum_total_C += $query_RAP_C->Total;
			// 	}

			// 	$biaya_B = 0;
			// 	$biaya_C = 0;
			// 	$biaya_A = 0;
			// 	foreach ($ekse_biaya->result() as $biaya) {
			// 		if ($biaya->Alokasi == 'B') {
			// 			$biaya_B = $biaya->TtlBLE;
			// 		}
			// 		if ($biaya->Alokasi == 'C') {
			// 			$biaya_C = $biaya->TtlBLE;
			// 		}
			// 		if ($biaya->Alokasi == 'A') {
			// 			$biaya_A = $biaya->TtlBLE;
			// 		}
			// 	}







			// 	$total_PAYMENT_KO = 0;
			// 	$total_REMAINING_KO = 0;
			// 	foreach ($ekse_biaya_KO->result() as $data_KO) {
			// 		// $total_REMAINING_KO += $data_KO->RemainingKO;
			// 		$total_REMAINING_KO += $data_KO->RemainingKO;
			// 	}

			// 	$sum_total_YAD_B = 0;
			// 	foreach ($ekse_YAD_B->result() as $query_YAD_B) {
			// 		$sum_total_YAD_B += $query_YAD_B->Amount;
			// 	}
			// 	// echo $sum_total_YAD_B;
			// 	// exit;

			// 	$sum_total_YAD_C = 0;
			// 	foreach ($ekse_YAD_C->result() as $query_YAD_C) {
			// 		$sum_total_YAD_C += $query_YAD_C->Amount;
			// 	}
			// 	$sum_total_A = $biaya_A; //Ambil RAP A dari Biaya A
			// 	$hitung_1_2_3 = $biaya_B + $biaya_C + $total_REMAINING_KO + $sum_total_YAD_B + $sum_total_YAD_C;
			// 	$sisa_kontrak = $hitung_netto - $hitung_1_2_3;
			// 	$sisa_RAP = ($sum_total_B + $sum_total_C) - $hitung_1_2_3;


			// 	// echo $persen_biaya;
			// 	// exit;

			// 	//hitung persen

			// 	$persen_rap_a = ($sum_total_A / $hitung_netto) * 100;
			// 	$persen_rap_b = ($sum_total_B / $hitung_netto) * 100;
			// 	$persen_rap_c = ($sum_total_C / $hitung_netto) * 100;
			// 	$persen_biaya = $hitung_1_2_3 / ($sum_total_B + $sum_total_C) * 100;

			// 	// echo $persen_biaya;
			// 	// exit;
			// 	$persen_sisa_kontrak = ($sisa_kontrak / $hitung_netto) * 100;
			// 	$persen_sisa_RAP = ($sisa_RAP / ($sum_total_B + $sum_total_C)) * 100;
			// 	// echo is_finite($persen_biaya)
			// 	// echo $hitung_netto;
			// 	// exit;

			// 	$data['bruto'] = $hitung_netto;
			// 	$data['RAP_A'] = $sum_total_A;
			// 	$data['RAP_B'] = $sum_total_B;
			// 	$data['RAP_C'] = $sum_total_C;

			// 	$data['biaya_A'] = $biaya_A;
			// 	$data['biaya_B'] = $biaya_B;
			// 	$data['biaya_C'] = $biaya_C;

			// 	$data['sisa_hutang_KO'] = $total_REMAINING_KO;
			// 	$data['sum_total_YAD_B'] = $sum_total_YAD_B;
			// 	$data['sum_total_YAD_C'] = $sum_total_YAD_C;
			// 	$data['hitung_1_2_3'] = $hitung_1_2_3;
			// 	$data['sisa_kontrak'] = $sisa_kontrak;
			// 	$data['sisa_RAP'] = $sisa_RAP;
			// 	$data['persen_rap_a'] = $persen_rap_a . "%";
			// 	$data['persen_rap_b'] = $persen_rap_b . "%";
			// 	$data['persen_rap_c'] = $persen_rap_c . "%";
			// 	$data['persen_biaya'] = $persen_biaya . "%";
			// 	$data['persen_sisa_kontrak'] = $persen_sisa_kontrak . "%";
			// 	$data['persen_sisa_RAP'] = $persen_sisa_RAP . "%";

			// 	$data['Curva_ArrayBuatan'] = $this->getDataCurva($this->input->post('JobNoChart'));
			// }


			// $tanggal = date('d');
			// $bulan = date('m');
			// $tahun = date('Y');
			// $date = $bulan . "/" . $tanggal . "/" . $tahun;

			// $Company = $this->config->item('Company');

			// $query_tampil_job_no = "SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND Company='$Company'";
			// $ekse_tampil_job_no = $this->db->query($query_tampil_job_no);




			// // $data['AbsensiPusat'] = $this->db->query("SELECT * FROM AbsenHarian where TglEntry = '$date' ORDER BY Divisi ASC")->result();

			// $data['Pelaksanaan'] = $this->dashboardmod->pelaksanaan();
			// $data['Proposal'] = $this->dashboardmod->Proposal();
			// $data['Gagal'] = $this->dashboardmod->Gagal();
			// $data['Pemeliharaan'] = $this->dashboardmod->Pemeliharaan();
			// $data['tampil_job_no'] = $ekse_tampil_job_no;

			// $data['TotalAsset'] =$this->dashboardmod->TotalAsset();

			$data['judul'] = 'Dashboard';


			// $tgl_awal = date('Y-m-21', strtotime('-1 month'));
			// $tgl_akhir = date('Y-m-20');

			// $data['tgl_awal'] = $tgl_awal;
			// $data['tgl_akhir'] = $tgl_akhir;

			// // $tgl_awal = '2022-06-21';
			// $tgl_akhir = '2022-07-20';


			// $cek_terlambat = $this->data_absen_harian($tgl_awal, $tgl_akhir);
			// $data['cek_terlambat'] = $cek_terlambat;

			// print_rr($cek_terlambat->result());
			// exit;
			// echo $tgl_akhir;
			// exit;

			// start get data forum diskusi
			// $data['forumDiskusi'] = $this->db->query("SELECT * FROM tblForumDiskusi WHERE IsActive = '1' ORDER BY Id DESC")->result_array();

			// end get data forum diskusi

			$data['bodyclass'] = 'sidebar-collapse skin-black';


			// $data['TOTAL'] = $this->db->query("select SUM(SubTotalHarga) as TOTAL From DataAsset")->row();
			// $data['GraphKategori'] = $this->db->query("DataAssetGraph")->result();
			// $data['PertumbuhanAsset'] = $this->db->query("SELECT 
			// 							Tahun, Pembelian, SUM(Pembelian) OVER(ORDER BY Tahun ROWS UNBOUNDED PRECEDING) AS Nilai
			// 						FROM dbo.tempDataAsset")->result();

			// $MyQuery = $this->db->query("SELECT Tahun, Pembelian, SUM(Pembelian) OVER(ORDER BY Tahun ROWS UNBOUNDED PRECEDING) AS Nilai FROM dbo.tempDataAsset")->result();

			// $ArrayTahun = array();
			// $ArrayTotal = array();
			// $ArrayPembelian = array();

			// foreach ($MyQuery as $row) {
			// 	$ArrayTahun[] = $row->Tahun;
			// 	$ArrayPembelian[] = $row->Pembelian;
			// 	$ArrayTotal[] = $row->Nilai;
			// }

			// $data['ArrayTahun'] = json_encode($ArrayTahun);
			// $data['ArrayPembelian'] = json_encode($ArrayPembelian, JSON_NUMERIC_CHECK);
			// $data['ArrayTotal'] = json_encode($ArrayTotal, JSON_NUMERIC_CHECK);
			// // print_rr($data['PertumbuhanAsset']);
			// exit;


			// $data['Area'] = $this->db->query("SELECT * FROM AREA")->result();



			// // === start wilayah tenancy
			// $tahun_tenant = date('Y');
			// $bulan_sekarang = date('n');
			// $bulan_akhir = $bulan_sekarang - 1;

			// // Jika Januari → tetap Januari
			// if ($bulan_akhir < 1) {
			// 	$bulan_akhir = 1;
			// }

			// $data['bulan_selected'] = $bulan_akhir;

			// $data['bulan_list'] = [
			// 	1 => 'Januari',
			// 	2 => 'Februari',
			// 	3 => 'Maret',
			// 	4 => 'April',
			// 	5 => 'Mei',
			// 	6 => 'Juni',
			// 	7 => 'Juli',
			// 	8 => 'Agustus',
			// 	9 => 'September',
			// 	10 => 'Oktober',
			// 	11 => 'November',
			// 	12 => 'Desember'
			// ];

			// // Ambil data dropdown
			// // $data['segmen_list'] = $this->getALLSegmen();

			// // Default nilai filter
			// $data['segmen_selected'] = $this->input->get('segmen') ?? 6;
			// $data['tahun_selected'] = $tahun_tenant;
			// // === end wilayah tenancy

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar');
			$this->load->view('Tenancy/DashboardTenancy', $data);
			$this->load->view('templates/footer');
		}

		private function getALLSegmen()
		{
			$this->db->select('SegmenID, NamaSegmen');
			$this->db->from('FA_Segmen');
			$this->db->order_by('SegmenID', 'ASC');

			$query = $this->db->get();
			return $query->result();
		}

		// private function data_absen_harian($tgl_awal, $tgl_akhir)
		// {

		// 	$myquery = $this->db->query(
		// 		"
		// 	SELECT NamaKaryawan, COUNT(*) AS JumlahTerlambat 
		// 	FROM AbsenHarian WHERE LokasiKerja='Pusat' AND Status='Terlambat' AND TglEntry>='$tgl_awal' AND TglEntry <='$tgl_akhir'
		// 	GROUP BY NamaKaryawan
		// 	"
		// 	);
		// 	return $myquery->result_array();
		// }

		function getDataCurva($JobNo_Curva = '')
		{
			if ($JobNo_Curva == '') {
				exit;
			}


			$result = $this->db->query("Report_GraphCurva N'{$JobNo_Curva}'");
			$Jumlah = $result->num_rows();

			$bulan = "";
			$rencana_progress = null;
			$realisasi_progress = null;
			$rencana_termin = null;
			$realisasi_termin = null;
			$realisasi_biaya = null;
			$rencana_biaya = null;

			$Curva_ArrayBuatan = array();

			if ($Jumlah == 0) {
				$Curva_ArrayBuatan = array(
					'bulan' => $bulan,
					'rencana_progress' => 0,
					'realisasi_progress' => 0,
					'rencana_termin' => 0,
					'realisasi_termin' => 0,
					'realisasi_biaya' => 0,
					'rencana_biaya' => 0,
					'rencana_biaya' => 0,
				);
			} else {
				foreach ($result->result() as $item) :

					$bul = date('M-Y', strtotime($item->Tanggal_Gabungan));
					$bulan .= "'$bul'" . ", ";

					$renprog = ($item->balance_rencana_progress == 0) ? NULL : $item->balance_rencana_progress;
					$rencana_progress .= "$renprog" . ", ";

					$realprog = ($item->balance_realisasi_progress == 0) ? NULL : $item->balance_realisasi_progress;
					$realisasi_progress .= "$realprog" . ", ";

					$retermin = ($item->Ballance_R_Termin1 == 0) ? NULL : $item->Ballance_R_Termin1;
					$rencana_termin .= "$retermin" . ", ";

					$realTermin = ($item->balance_realisasi_termin == 0) ? NULL : $item->balance_realisasi_termin;
					$realisasi_termin .= "$realTermin" . ", ";

					$realBiaya = ($item->balance_realisasi_biaya_rap == 0) ? NULL : $item->balance_realisasi_biaya_rap;
					$realisasi_biaya .= "$realBiaya" . ", ";

					$rencanaBiaya = ($item->balance_rencana_biaya_rap == 0) ? NULL : $item->balance_rencana_biaya_rap;
					$rencana_biaya .= "$rencanaBiaya" . ", ";

					$Curva_ArrayBuatan = array(
						'bulan' => $bulan,
						'rencana_progress' => $rencana_progress,
						'realisasi_progress' => $realisasi_progress,
						'rencana_termin' => $rencana_termin,
						'realisasi_termin' => $realisasi_termin,
						'realisasi_biaya' => $realisasi_biaya,
						'rencana_biaya' => $rencana_biaya,
						'rencana_biaya' => $rencana_biaya,
					);
				endforeach;
			}

			return (object) $Curva_ArrayBuatan;


			// $data['Curva_ArrayBuatan'] = (object) $Curva_ArrayBuatan;
		}

		function tampilkan_curva()
		{
			if (!$this->input->post('JobNo_Curva')) {
				die;
			}

			$JobNo_Curva = $this->input->post('JobNo_Curva', TRUE);
			$result = $this->db->query("Report_GraphCurva N'{$JobNo_Curva}'");
			$Jumlah = $result->num_rows();

			$bulan = "";
			$rencana_progress = null;
			$realisasi_progress = null;
			$rencana_termin = null;
			$realisasi_termin = null;
			$realisasi_biaya = null;
			$rencana_biaya = null;

			$Curva_ArrayBuatan = array();

			if ($Jumlah == 0) {
				$Curva_ArrayBuatan = array(
					'bulan' => $bulan,
					'rencana_progress' => 0,
					'realisasi_progress' => 0,
					'rencana_termin' => 0,
					'realisasi_termin' => 0,
					'realisasi_biaya' => 0,
					'rencana_biaya' => 0,
					'rencana_biaya' => 0,
				);
			} else {
				foreach ($result->result() as $item) :

					$bul = date('M-Y', strtotime($item->Tanggal_Gabungan));
					$bulan .= "'$bul'" . ", ";

					$renprog = ($item->balance_rencana_progress == 0) ? NULL : $item->balance_rencana_progress;
					$rencana_progress .= "$renprog" . ", ";

					$realprog = ($item->balance_realisasi_progress == 0) ? NULL : $item->balance_realisasi_progress;
					$realisasi_progress .= "$realprog" . ", ";

					$retermin = ($item->Ballance_R_Termin1 == 0) ? NULL : $item->Ballance_R_Termin1;
					$rencana_termin .= "$retermin" . ", ";

					$realTermin = ($item->balance_realisasi_termin == 0) ? NULL : $item->balance_realisasi_termin;
					$realisasi_termin .= "$realTermin" . ", ";

					$realBiaya = ($item->balance_realisasi_biaya_rap == 0) ? NULL : $item->balance_realisasi_biaya_rap;
					$realisasi_biaya .= "$realBiaya" . ", ";

					$rencanaBiaya = ($item->balance_rencana_biaya_rap == 0) ? NULL : $item->balance_rencana_biaya_rap;
					$rencana_biaya .= "$rencanaBiaya" . ", ";

					$Curva_ArrayBuatan = array(
						'bulan' => $bulan,
						'rencana_progress' => $rencana_progress,
						'realisasi_progress' => $realisasi_progress,
						'rencana_termin' => $rencana_termin,
						'realisasi_termin' => $realisasi_termin,
						'realisasi_biaya' => $realisasi_biaya,
						'rencana_biaya' => $rencana_biaya,
						'rencana_biaya' => $rencana_biaya,
					);
				endforeach;
			}


			$data['Curva_ArrayBuatan'] = (object) $Curva_ArrayBuatan;
			$this->load->view('GrafikCurva', $data);
			// print_rr($result->result());
			// print_rr($result);
		}
	}
