<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Report extends CI_Controller
{
	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model(array("M_report"));
	}

	public function SaldoRek()
	{
		$data['rek'] = GetRekening();
		$data['judul'] = 'SALDO REKENING';
		$data['tgl_skrg'] = date('Y-m-d');
		$data['tgl_kemarin'] = date('Y-m-d', strtotime("-7 day", strtotime(date("Y-m-d"))));
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/SaldoRek", $data);
		$this->load->view('templates/footer');
	}

	function GetTableRek()
	{
		if ($this->input->post('RekId')) {
			$RekId = $this->input->post('RekId');
			$Periode1 = ($this->input->post('Periode1')) ? $this->input->post('Periode1') : '';
			$Periode2 = ($this->input->post('Periode2')) ? $this->input->post('Periode2') : '';
			$RekIdPecah = explode(' ', $RekId);
			$RekIdPecah = $RekIdPecah[0];
			$query = $this->M_report->getData($RekId, $RekIdPecah, $Periode1, $Periode2);

			// if ($Periode1 != '' && $Periode2 != '') {
			// 	$query = $this->M_report->getData($RekId, Periode1, Periode2);
			// }

			$data['Periode1'] = $Periode1;
			$data['Periode2'] = $Periode2;
			$data['RekId'] = $RekId;
			$data['RekIdPecah'] = $RekIdPecah;
			$data['rek'] = $query;



			// print_rr($query);
			// exit;
			$this->load->view('rekening/table-rekening', $data);
		}
	}

	public function MenuRptPO()
	{


		$data['judul'] = 'Menu Report Purchasing';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Procurement/MenuRptPO", $data);
		$this->load->view('templates/footer');
	}

	public function MenuRptPDPJ()
	{

		$data['judul'] = 'Menu Report PD & PJ ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/MenuRptPDPJ", $data);
		$this->load->view('templates/footer');
	}

	public function MenuRptOperasi()
	{

		$data['judul'] = 'Menu Report Operation  ';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/MenuRptOperasi", $data);
		$this->load->view('templates/footer');
	}

	public function QueryTermin()
	{

		$ListSesi = $this->session->userdata('MIS_LOGGED_TOKEN');

		$json = json_decode($ListSesi, true);
		$DataSesiUser = $json['UserID'];
		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$DataSesiUser' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode("','", $explode) . "'";

		$data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		$data['judul'] = 'Query Termin';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmQueryTermin", $data);
		$this->load->view('templates/footer');
	}

	public function MonitoringTermin()
	{
		$getStatusJob = $this->db->query("select distinct(StatusJob) From Job WHERE StatusJob='Pelaksanaan' OR StatusJob='Pemeliharaan' OR StatusJob='Closed' ORDER BY StatusJob DESC")->result();
		$data['sj'] = $getStatusJob;

		$data['judul'] = 'Monitoring Termin  ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmMonitoringTermin", $data);
		$this->load->view('templates/footer');
	}

	public function PiutangProgress()
	{
		$this->load->Model('m_report');
		$query = $this->m_report->QueryPiutangProgressFisik();
		$data['piutangprogress'] = $query;

		$data['judul'] = 'Piutang Progress Fisik ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmPiutangProgressFisik", $data);
		$this->load->view('templates/footer');
	}

	public function Barchart()
	{
		error_reporting(0);
		$savePath = FCPATH . '/images/chart_image.png';

		// Check if the file already exists
		if (file_exists($savePath)) {
			// If it exists, delete the file
			unlink($savePath);
		}
		$this->load->Model('m_report');

		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$data['Job'] = $this->db->query("SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND Company='$this->Company'")->result();


		$data['bruto'] = '';
		$data['RAP_A'] = 0;
		$data['RAP_B'] = 0;
		$data['RAP_C'] = 0;
		$data['biaya_B'] = '';
		$data['biaya_C'] = '';
		$data['sisa_hutang_KO'] = '';
		$data['sum_total_YAD_B'] = '';
		$data['sum_total_YAD_C'] = '';
		$data['hitung_1_2_3'] = '';
		$data['sisa_kontrak'] = '';
		$data['sisa_RAP'] = '';
		$data['JobNoChart'] = '';


		//data baru
		$data['total_RAP'] = 0;
		$data['gross_profit_kontrak'] = 0;
		$data['total_realisasi_biaya'] = 0;
		$data['total_YAD'] = 0;
		$data['persen_total_RAP'] = 0;

		//berakhir data baru

		$persen_rap_a = 0;
		$persen_rap_b = 0;
		$persen_rap_c = 0;
		$persen_biaya = 0;
		$persen_sisa_kontrak = 0;
		$persen_sisa_RAP = 0;


		//data termin diterima
		$data['total_termin_diterima'] = 0;
		$total_termin_diterima = 0;
		$sisa_biaya_terhadap_termin = 0;
		$sisa_rap_terhadap_termin = 0;

		$total_biaya_baru = 0;
		$persen_biaya_baru = 0;
		$sisa_biaya_terhadap_kontrak_baru = 0;
		$persen_sisa_biaya_terhadap_kontrak_baru = 0;
		$sisa_biaya_rap_terhadap_kontrak = 0;
		$persen_sisa_biaya_rap_terhadap_kontrak = 0;

		//berakhir data termin diterima



		//awal data terhadap sisa termin
		$sisa_penerimaan_termin_saat_ini = 0;
		$total_biaya_penerimaan_termin_saat_ini = 0;
		$sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini = 0;
		$sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini = 0;
		//akhir data terhadap sisa termin


		if (isset($_POST['JobNoChart'])) {
			$JobNo = $this->input->post('JobNoChart');
			// echo $JobNo;
			// exit;
			$data['JobNoChart'] = $JobNo;

			$query_kontrak = "SELECT * FROM Job WHERE JobNo='$JobNo' ";
			$ekse_kontrak = $this->db->query($query_kontrak)->row();

			$query_RAP_A = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='A' AND Tipe !='Header' ";
			$ekse_RAP_A = $this->db->query($query_RAP_A);
			$query_RAP_B = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='B' AND Tipe !='Header' ";
			$ekse_RAP_B = $this->db->query($query_RAP_B);

			$query_RAP_C = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='C' AND Tipe !='Header' ";
			$ekse_RAP_C = $this->db->query($query_RAP_C);

			$query_biaya = "SELECT Alokasi, ISNULL(SUM(Amount),0) AS 'TtlBLE' FROM BLE WHERE JobNo='$JobNo'  GROUP BY Alokasi";
			$ekse_biaya = $this->db->query($query_biaya);

			$query_biaya_KO = "SELECT A.Jobno AS JobNo, D.JobNm, A.NoKO, A.TglKO, A.KategoriId, A.VendorId, B.VendorNm, A.SubTotal-A.DiscAmount+A.PPN AS TotalKO, D.Company,
			ISNULL((SELECT SUM(Amount) FROM BLE WHERE NoKO=A.NoKO),0) AS PaymentKO,
			(CASE
				WHEN A.ClosedBy IS NULL THEN
				A.SubTotal-A.DiscAmount+A.PPN - ISNULL((SELECT SUM(AMOUNT) FROM BLE WHERE NoKO=A.NoKO),0)
				ELSE
				'0'
				END) as 'RemainingKO'
			FROM KoHdr A
			LEFT JOIN Vendor B ON B.VendorId=A.VendorId
			LEFT JOIN Job D  ON D.JobNo=A.JobNo
			WHERE A.JobNo='$JobNo'  AND A.ApprovedBy IS NOT NULL AND A.CanceledBy IS NULL";
			$ekse_biaya_KO = $this->db->query($query_biaya_KO);
			$query_YAD_B = "SELECT * FROM YAD WHERE JobNo='$JobNo' AND Alokasi='B' ";
			$ekse_YAD_B = $this->db->query($query_YAD_B);

			$query_YAD_C = "SELECT * FROM YAD WHERE JobNo='$JobNo' AND Alokasi='C' ";
			$ekse_YAD_C = $this->db->query($query_YAD_C);



			$bruto = 0;
			if ($ekse_kontrak->KSO == 1) {

				if ($ekse_kontrak->Own == 1) {
					$bruto = ($ekse_kontrak->Bruto * $ekse_kontrak->PersenShare1) / 100;
				} else {
					$bruto = ($ekse_kontrak->Bruto * $ekse_kontrak->PersenShare2) / 100;
				}

				if (CariKataLoan($ekse_kontrak->SumberDana) != 'LOAN') {
					$hitung_netto1 = $bruto / 1.1;
					$hitung_netto = $hitung_netto1 * 0.97;
					// echo 'ok';
				} else {
					$hitung_netto = $bruto;
					// echo 'no';
				}


				// echo CariKataLoan($ekse_kontrak->SumberDana);
				// exit;

			} else {

				$bruto = $ekse_kontrak->Bruto;
				$hitung_netto = $bruto;
			}

			$sum_total_A = 0;
			$persen_rap_a = 0;
			foreach ($ekse_RAP_A->result() as $query_RAP_A) {
				$sum_total_A += $query_RAP_A->Total;
			}

			$sum_total_B = 0;
			$persen_rap_b = 0;
			foreach ($ekse_RAP_B->result() as $query_RAP_B) {
				$sum_total_B += $query_RAP_B->Total;
			}

			$sum_total_C = 0;
			foreach ($ekse_RAP_C->result() as $query_RAP_C) {
				$sum_total_C += $query_RAP_C->Total;
			}

			$biaya_B = 0;
			$biaya_C = 0;
			$biaya_A = 0;
			foreach ($ekse_biaya->result() as $biaya) {
				if ($biaya->Alokasi == 'B') {
					$biaya_B = $biaya->TtlBLE;
				}
				if ($biaya->Alokasi == 'C') {
					$biaya_C = $biaya->TtlBLE;
				}
				if ($biaya->Alokasi == 'A') {
					$biaya_A = $biaya->TtlBLE;
				}
			}


			$total_PAYMENT_KO = 0;
			$total_REMAINING_KO = 0;
			foreach ($ekse_biaya_KO->result() as $data_KO) {
				// $total_REMAINING_KO += $data_KO->RemainingKO;
				$total_REMAINING_KO += $data_KO->RemainingKO;
			}

			$sum_total_YAD_B = 0;
			foreach ($ekse_YAD_B->result() as $query_YAD_B) {
				$sum_total_YAD_B += $query_YAD_B->Amount;
			}
			// echo $sum_total_YAD_B;
			// exit;

			$sum_total_YAD_C = 0;
			foreach ($ekse_YAD_C->result() as $query_YAD_C) {
				$sum_total_YAD_C += $query_YAD_C->Amount;
			}
			$sum_total_A = $biaya_A; //Ambil RAP A dari Biaya A
			$hitung_1_2_3 = $biaya_B + $biaya_C + $total_REMAINING_KO + $sum_total_YAD_B + $sum_total_YAD_C;
			$sisa_kontrak = $hitung_netto - $hitung_1_2_3;
			$sisa_RAP = ($sum_total_B + $sum_total_C) - $hitung_1_2_3;


			// echo $persen_biaya;
			// exit;

			//hitung persen

			$persen_rap_a = ($sum_total_A / $hitung_netto) * 100;
			$persen_rap_b = ($sum_total_B / $hitung_netto) * 100;
			$persen_rap_c = ($sum_total_C / $hitung_netto) * 100;
			$persen_biaya = $hitung_1_2_3 / ($sum_total_B + $sum_total_C) * 100;

			// echo $persen_biaya;
			// exit;
			$persen_sisa_kontrak = ($sisa_kontrak / $hitung_netto) * 100;
			$persen_sisa_RAP = ($sisa_RAP / ($sum_total_B + $sum_total_C)) * 100;
			// echo is_finite($persen_biaya)
			// echo $hitung_netto;
			// exit;

			//data baru perhitungan baru

			$total_RAP = $sum_total_A + $sum_total_B + $sum_total_C;
			$gross_profit_kontrak = $hitung_netto - $total_RAP;
			$total_realisasi_biaya = $biaya_A + $biaya_B + $biaya_C;
			$total_YAD = $sum_total_YAD_B + $sum_total_YAD_C;
			$persen_total_RAP = ($total_RAP / $hitung_netto) * 100;
			$total_biaya_baru = $total_realisasi_biaya + $total_REMAINING_KO + $total_YAD;
			$persen_biaya_baru = ($total_biaya_baru / $hitung_netto) * 100;
			$sisa_biaya_terhadap_kontrak_baru = $hitung_netto - $total_biaya_baru;
			$persen_sisa_biaya_terhadap_kontrak_baru = ($sisa_biaya_terhadap_kontrak_baru / $hitung_netto) * 100;
			$sisa_biaya_rap_terhadap_kontrak = $total_RAP - $total_biaya_baru;
			$persen_sisa_biaya_rap_terhadap_kontrak = ($sisa_biaya_rap_terhadap_kontrak / $total_RAP) * 100;
			// echo $persen_sisa_biaya_rap_terhadap_kontrak;
			// exit;
			//berakhir data baru
			// echo $persen_sisa_biaya_terhadap_kontrak_baru;
			// exit;



			//hitung termin diterima
			$query_termin_diterima = $this->M_report->getDataTermin($JobNo);
			$row_termin = $query_termin_diterima->row();
			$total_termin_diterima = $row_termin->TotalTermin;

			if (CariKataLoan($row_termin->SumberDana) != 'LOAN') {
				$total_termin_diterima = ($total_termin_diterima / 1.1) * 0.97;
			} else {
				$total_termin_diterima = $total_termin_diterima;
			}

			$sisa_biaya_terhadap_termin = $total_termin_diterima - $total_realisasi_biaya;
			$buat_persen_rap = $persen_total_RAP / 100;
			$sisa_rap_terhadap_termin = ($total_termin_diterima * $buat_persen_rap) - $total_realisasi_biaya;

			// echo $sisa_rap_terhadap_termin;
			// exit;

			//berakhir termin diterima


			//sisa penerimaan termin saat ini
			$sisa_penerimaan_termin_saat_ini = $hitung_netto - $total_termin_diterima;
			$total_biaya_penerimaan_termin_saat_ini = $total_REMAINING_KO - $total_YAD;
			$sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini = $sisa_penerimaan_termin_saat_ini - $total_biaya_penerimaan_termin_saat_ini;
			$sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini = ($sisa_penerimaan_termin_saat_ini * $buat_persen_rap) - $total_biaya_penerimaan_termin_saat_ini;
			// echo $sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini;
			// exit; 
			//akhir penerimaaan termin saat ini

			$data['bruto'] = $hitung_netto;
			$data['RAP_A'] = $sum_total_A;
			$data['RAP_B'] = $sum_total_B;
			$data['RAP_C'] = $sum_total_C;

			$data['biaya_A'] = $biaya_A;
			$data['biaya_B'] = $biaya_B;
			$data['biaya_C'] = $biaya_C;

			$data['sisa_hutang_KO'] = $total_REMAINING_KO;
			$data['sum_total_YAD_B'] = $sum_total_YAD_B;
			$data['sum_total_YAD_C'] = $sum_total_YAD_C;
			$data['hitung_1_2_3'] = $hitung_1_2_3;
			$data['sisa_kontrak'] = $sisa_kontrak;
			$data['sisa_RAP'] = $sisa_RAP;
			$data['persen_rap_a'] = $persen_rap_a . "%";
			$data['persen_rap_b'] = $persen_rap_b . "%";
			$data['persen_rap_c'] = $persen_rap_c . "%";
			$data['persen_biaya'] = $persen_biaya . "%";
			$data['persen_sisa_kontrak'] = $persen_sisa_kontrak . "%";
			$data['persen_sisa_RAP'] = $persen_sisa_RAP . "%";

			//data baru
			$data['total_RAP'] = $total_RAP;
			$data['gross_profit_kontrak'] = $gross_profit_kontrak;
			$data['total_realisasi_biaya'] = $total_realisasi_biaya;
			$data['total_YAD'] = $total_YAD;
			$data['persen_total_RAP'] = $persen_total_RAP;

			//berakhir data baru

			//awal data termin diterima
			$data['total_termin_diterima'] = $total_termin_diterima;
			//berakhir termin diterima
			$data['sisa_biaya_terhadap_termin'] = $sisa_biaya_terhadap_termin;
			$data['sisa_rap_terhadap_termin'] = $sisa_rap_terhadap_termin;
			$data['total_biaya_baru'] = $total_biaya_baru;
			$data['persen_biaya_baru'] = $persen_biaya_baru;
			$data['sisa_biaya_terhadap_kontrak_baru'] = $sisa_biaya_terhadap_kontrak_baru;
			$data['persen_sisa_biaya_terhadap_kontrak_baru'] = $persen_sisa_biaya_terhadap_kontrak_baru;
			$data['sisa_biaya_rap_terhadap_kontrak'] = $sisa_biaya_rap_terhadap_kontrak;
			$data['persen_sisa_biaya_rap_terhadap_kontrak'] = $persen_sisa_biaya_rap_terhadap_kontrak;


			//awal data sisa penerimaan termin saat ini;
			$data['sisa_penerimaan_termin_saat_ini'] = $sisa_penerimaan_termin_saat_ini;
			$data['total_biaya_penerimaan_termin_saat_ini'] = $total_biaya_penerimaan_termin_saat_ini;
			$data['sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini'] = $sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini;
			$data['sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini'] = $sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini;

			//akhir data sisa penerimaan termin saat ini
			$data['nama_job'] = $this->db->query("SELECT CONCAT_WS(' - ',JobNo,JobNm) as nama_job FROM Job WHERE JobNo='$JobNo' ")->row()->nama_job;
			$data['nomor_job'] = $JobNo;
		}

		$query_tampil_job_no = "SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND Company='$this->Company'  ";
		$ekse_tampil_job_no = $this->db->query($query_tampil_job_no);




		$data['tampil_job_no'] = $ekse_tampil_job_no;


		// print_rr($data);
		// exit();
		// print_rr($data);
		// exit;
		$data['judul'] = 'Bar Chart Proyek ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmBarChart", $data);
		$this->load->view('templates/footer');
	}

	public function exportPDFBarChartProyek($JobNo)
	{
		error_reporting(0);
		$this->load->Model('m_report');

		$this->data['user'] = $this->session->userdata('MIS_LOGGED_ID');

		$this->data['Job'] = $this->db->query("SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND Company='$this->Company'")->result();


		$this->data['bruto'] = '';
		$this->data['RAP_A'] = 0;
		$this->data['RAP_B'] = 0;
		$this->data['RAP_C'] = 0;
		$this->data['biaya_B'] = '';
		$this->data['biaya_C'] = '';
		$this->data['sisa_hutang_KO'] = '';
		$this->data['sum_total_YAD_B'] = '';
		$this->data['sum_total_YAD_C'] = '';
		$this->data['hitung_1_2_3'] = '';
		$this->data['sisa_kontrak'] = '';
		$this->data['sisa_RAP'] = '';
		$this->data['JobNoChart'] = '';


		//this->data baru
		$this->data['total_RAP'] = 0;
		$this->data['gross_profit_kontrak'] = 0;
		$this->data['total_realisasi_biaya'] = 0;
		$this->data['total_YAD'] = 0;
		$this->data['persen_total_RAP'] = 0;

		//berakhir data baru

		$persen_rap_a = 0;
		$persen_rap_b = 0;
		$persen_rap_c = 0;
		$persen_biaya = 0;
		$persen_sisa_kontrak = 0;
		$persen_sisa_RAP = 0;


		//data termin diterima
		$this->data['total_termin_diterima'] = 0;
		$total_termin_diterima = 0;
		$sisa_biaya_terhadap_termin = 0;
		$sisa_rap_terhadap_termin = 0;

		$total_biaya_baru = 0;
		$persen_biaya_baru = 0;
		$sisa_biaya_terhadap_kontrak_baru = 0;
		$persen_sisa_biaya_terhadap_kontrak_baru = 0;
		$sisa_biaya_rap_terhadap_kontrak = 0;
		$persen_sisa_biaya_rap_terhadap_kontrak = 0;

		//berakhir data termin diterima



		//awal data terhadap sisa termin
		$sisa_penerimaan_termin_saat_ini = 0;
		$total_biaya_penerimaan_termin_saat_ini = 0;
		$sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini = 0;
		$sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini = 0;
		//akhir data terhadap sisa termin


		$this->data['JobNoChart'] = $JobNo;

		$query_kontrak = "SELECT * FROM Job WHERE JobNo='$JobNo' ";
		$ekse_kontrak = $this->db->query($query_kontrak)->row();

		$query_RAP_A = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='A' AND Tipe !='Header' ";
		$ekse_RAP_A = $this->db->query($query_RAP_A);
		$query_RAP_B = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='B' AND Tipe !='Header' ";
		$ekse_RAP_B = $this->db->query($query_RAP_B);

		$query_RAP_C = "SELECT SUM(Vol*HrgSatuan) as Total  FROM RAP WHERE JobNo='$JobNo' AND Alokasi='C' AND Tipe !='Header' ";
		$ekse_RAP_C = $this->db->query($query_RAP_C);

		$query_biaya = "SELECT Alokasi, ISNULL(SUM(Amount),0) AS 'TtlBLE' FROM BLE WHERE JobNo='$JobNo'  GROUP BY Alokasi";
		$ekse_biaya = $this->db->query($query_biaya);

		$query_biaya_KO = "SELECT A.Jobno AS JobNo, D.JobNm, A.NoKO, A.TglKO, A.KategoriId, A.VendorId, B.VendorNm, A.SubTotal-A.DiscAmount+A.PPN AS TotalKO, D.Company,
			ISNULL((SELECT SUM(Amount) FROM BLE WHERE NoKO=A.NoKO),0) AS PaymentKO,
			(CASE
				WHEN A.ClosedBy IS NULL THEN
				A.SubTotal-A.DiscAmount+A.PPN - ISNULL((SELECT SUM(AMOUNT) FROM BLE WHERE NoKO=A.NoKO),0)
				ELSE
				'0'
				END) as 'RemainingKO'
			FROM KoHdr A
			LEFT JOIN Vendor B ON B.VendorId=A.VendorId
			LEFT JOIN Job D  ON D.JobNo=A.JobNo
			WHERE A.JobNo='$JobNo'  AND A.ApprovedBy IS NOT NULL AND A.CanceledBy IS NULL";
		$ekse_biaya_KO = $this->db->query($query_biaya_KO);
		$query_YAD_B = "SELECT * FROM YAD WHERE JobNo='$JobNo' AND Alokasi='B' ";
		$ekse_YAD_B = $this->db->query($query_YAD_B);

		$query_YAD_C = "SELECT * FROM YAD WHERE JobNo='$JobNo' AND Alokasi='C' ";
		$ekse_YAD_C = $this->db->query($query_YAD_C);



		$bruto = 0;
		if ($ekse_kontrak->KSO == 1) {

			if ($ekse_kontrak->Own == 1) {
				$bruto = ($ekse_kontrak->Bruto * $ekse_kontrak->PersenShare1) / 100;
			} else {
				$bruto = ($ekse_kontrak->Bruto * $ekse_kontrak->PersenShare2) / 100;
			}

			if (CariKataLoan($ekse_kontrak->SumberDana) != 'LOAN') {
				$hitung_netto1 = $bruto / 1.1;
				$hitung_netto = $hitung_netto1 * 0.97;
				// echo 'ok';
			} else {
				$hitung_netto = $bruto;
				// echo 'no';
			}


			// echo CariKataLoan($ekse_kontrak->SumberDana);
			// exit;

		} else {

			$bruto = $ekse_kontrak->Bruto;
			$hitung_netto = $bruto;
		}

		$sum_total_A = 0;
		$persen_rap_a = 0;
		foreach ($ekse_RAP_A->result() as $query_RAP_A) {
			$sum_total_A += $query_RAP_A->Total;
		}

		$sum_total_B = 0;
		$persen_rap_b = 0;
		foreach ($ekse_RAP_B->result() as $query_RAP_B) {
			$sum_total_B += $query_RAP_B->Total;
		}

		$sum_total_C = 0;
		foreach ($ekse_RAP_C->result() as $query_RAP_C) {
			$sum_total_C += $query_RAP_C->Total;
		}

		$biaya_B = 0;
		$biaya_C = 0;
		$biaya_A = 0;
		foreach ($ekse_biaya->result() as $biaya) {
			if ($biaya->Alokasi == 'B') {
				$biaya_B = $biaya->TtlBLE;
			}
			if ($biaya->Alokasi == 'C') {
				$biaya_C = $biaya->TtlBLE;
			}
			if ($biaya->Alokasi == 'A') {
				$biaya_A = $biaya->TtlBLE;
			}
		}


		$total_PAYMENT_KO = 0;
		$total_REMAINING_KO = 0;
		foreach ($ekse_biaya_KO->result() as $data_KO) {
			// $total_REMAINING_KO += $data_KO->RemainingKO;
			$total_REMAINING_KO += $data_KO->RemainingKO;
		}

		$sum_total_YAD_B = 0;
		foreach ($ekse_YAD_B->result() as $query_YAD_B) {
			$sum_total_YAD_B += $query_YAD_B->Amount;
		}
		// echo $sum_total_YAD_B;
		// exit;

		$sum_total_YAD_C = 0;
		foreach ($ekse_YAD_C->result() as $query_YAD_C) {
			$sum_total_YAD_C += $query_YAD_C->Amount;
		}
		$sum_total_A = $biaya_A; //Ambil RAP A dari Biaya A
		$hitung_1_2_3 = $biaya_B + $biaya_C + $total_REMAINING_KO + $sum_total_YAD_B + $sum_total_YAD_C;
		$sisa_kontrak = $hitung_netto - $hitung_1_2_3;
		$sisa_RAP = ($sum_total_B + $sum_total_C) - $hitung_1_2_3;


		// echo $persen_biaya;
		// exit;

		//hitung persen

		$persen_rap_a = ($sum_total_A / $hitung_netto) * 100;
		$persen_rap_b = ($sum_total_B / $hitung_netto) * 100;
		$persen_rap_c = ($sum_total_C / $hitung_netto) * 100;
		$persen_biaya = $hitung_1_2_3 / ($sum_total_B + $sum_total_C) * 100;

		// echo $persen_biaya;
		// exit;
		$persen_sisa_kontrak = ($sisa_kontrak / $hitung_netto) * 100;
		$persen_sisa_RAP = ($sisa_RAP / ($sum_total_B + $sum_total_C)) * 100;
		// echo is_finite($persen_biaya)
		// echo $hitung_netto;
		// exit;

		//data baru perhitungan baru

		$total_RAP = $sum_total_A + $sum_total_B + $sum_total_C;
		$gross_profit_kontrak = $hitung_netto - $total_RAP;
		$total_realisasi_biaya = $biaya_A + $biaya_B + $biaya_C;
		$total_YAD = $sum_total_YAD_B + $sum_total_YAD_C;
		$persen_total_RAP = ($total_RAP / $hitung_netto) * 100;
		$total_biaya_baru = $total_realisasi_biaya + $total_REMAINING_KO + $total_YAD;
		$persen_biaya_baru = ($total_biaya_baru / $hitung_netto) * 100;
		$sisa_biaya_terhadap_kontrak_baru = $hitung_netto - $total_biaya_baru;
		$persen_sisa_biaya_terhadap_kontrak_baru = ($sisa_biaya_terhadap_kontrak_baru / $hitung_netto) * 100;
		$sisa_biaya_rap_terhadap_kontrak = $total_RAP - $total_biaya_baru;
		$persen_sisa_biaya_rap_terhadap_kontrak = ($sisa_biaya_rap_terhadap_kontrak / $total_RAP) * 100;
		// echo $persen_sisa_biaya_rap_terhadap_kontrak;
		// exit;
		//berakhir data baru
		// echo $persen_sisa_biaya_terhadap_kontrak_baru;
		// exit;



		//hitung termin diterima
		$query_termin_diterima = $this->M_report->getDataTermin($JobNo);
		$row_termin = $query_termin_diterima->row();
		$total_termin_diterima = $row_termin->TotalTermin;

		if (CariKataLoan($row_termin->SumberDana) != 'LOAN') {
			$total_termin_diterima = ($total_termin_diterima / 1.1) * 0.97;
		} else {
			$total_termin_diterima = $total_termin_diterima;
		}

		$sisa_biaya_terhadap_termin = $total_termin_diterima - $total_realisasi_biaya;
		$buat_persen_rap = $persen_total_RAP / 100;
		$sisa_rap_terhadap_termin = ($total_termin_diterima * $buat_persen_rap) - $total_realisasi_biaya;

		// echo $sisa_rap_terhadap_termin;
		// exit;

		//berakhir termin diterima


		//sisa penerimaan termin saat ini
		$sisa_penerimaan_termin_saat_ini = $hitung_netto - $total_termin_diterima;
		$total_biaya_penerimaan_termin_saat_ini = $total_REMAINING_KO - $total_YAD;
		$sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini = $sisa_penerimaan_termin_saat_ini - $total_biaya_penerimaan_termin_saat_ini;
		$sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini = ($sisa_penerimaan_termin_saat_ini * $buat_persen_rap) - $total_biaya_penerimaan_termin_saat_ini;
		// echo $sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini;
		// exit; 
		//akhir penerimaaan termin saat ini

		$this->data['bruto'] = $hitung_netto;
		$this->data['RAP_A'] = $sum_total_A;
		$this->data['RAP_B'] = $sum_total_B;
		$this->data['RAP_C'] = $sum_total_C;

		$this->data['biaya_A'] = $biaya_A;
		$this->data['biaya_B'] = $biaya_B;
		$this->data['biaya_C'] = $biaya_C;

		$this->data['sisa_hutang_KO'] = $total_REMAINING_KO;
		$this->data['sum_total_YAD_B'] = $sum_total_YAD_B;
		$this->data['sum_total_YAD_C'] = $sum_total_YAD_C;
		$this->data['hitung_1_2_3'] = $hitung_1_2_3;
		$this->data['sisa_kontrak'] = $sisa_kontrak;
		$this->data['sisa_RAP'] = $sisa_RAP;
		$this->data['persen_rap_a'] = $persen_rap_a . "%";
		$this->data['persen_rap_b'] = $persen_rap_b . "%";
		$this->data['persen_rap_c'] = $persen_rap_c . "%";
		$this->data['persen_biaya'] = $persen_biaya . "%";
		$this->data['persen_sisa_kontrak'] = $persen_sisa_kontrak . "%";
		$this->data['persen_sisa_RAP'] = $persen_sisa_RAP . "%";

		//data baru
		$this->data['total_RAP'] = $total_RAP;
		$this->data['gross_profit_kontrak'] = $gross_profit_kontrak;
		$this->data['total_realisasi_biaya'] = $total_realisasi_biaya;
		$this->data['total_YAD'] = $total_YAD;
		$this->data['persen_total_RAP'] = $persen_total_RAP;

		//berakhir data baru

		//awal data termin diterima
		$this->data['total_termin_diterima'] = $total_termin_diterima;
		//berakhir termin diterima
		$this->data['sisa_biaya_terhadap_termin'] = $sisa_biaya_terhadap_termin;
		$this->data['sisa_rap_terhadap_termin'] = $sisa_rap_terhadap_termin;
		$this->data['total_biaya_baru'] = $total_biaya_baru;
		$this->data['persen_biaya_baru'] = $persen_biaya_baru;
		$this->data['sisa_biaya_terhadap_kontrak_baru'] = $sisa_biaya_terhadap_kontrak_baru;
		$this->data['persen_sisa_biaya_terhadap_kontrak_baru'] = $persen_sisa_biaya_terhadap_kontrak_baru;
		$this->data['sisa_biaya_rap_terhadap_kontrak'] = $sisa_biaya_rap_terhadap_kontrak;
		$this->data['persen_sisa_biaya_rap_terhadap_kontrak'] = $persen_sisa_biaya_rap_terhadap_kontrak;


		//awal data sisa penerimaan termin saat ini;
		$this->data['sisa_penerimaan_termin_saat_ini'] = $sisa_penerimaan_termin_saat_ini;
		$this->data['total_biaya_penerimaan_termin_saat_ini'] = $total_biaya_penerimaan_termin_saat_ini;
		$this->data['sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini'] = $sisa_biaya_terhadap_termin_penerimaaan_termin_saat_ini;
		$this->data['sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini'] = $sisa_biaya_terhadap_rap_penerimaaan_termin_saat_ini;

		//akhir data sisa penerimaan termin saat ini
		$this->data['nama_job'] = $this->db->query("SELECT CONCAT_WS(' - ',JobNo,JobNm) as nama_job FROM Job WHERE JobNo='$JobNo' ")->row()->nama_job;
		$this->data['nomor_job'] = $JobNo;


		$query_tampil_job_no = "SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND Company='$this->Company'  ";
		$ekse_tampil_job_no = $this->db->query($query_tampil_job_no);




		$data['tampil_job_no'] = $ekse_tampil_job_no;


		$data['judul'] = 'Bar Chart Proyek ';

		$this->load->library('pdfgenerator');
		$file_pdf = 'BarChartProyek';
		$paper = 'A4';
		$orientation = "potrait";
		$html = $this->load->view('content/reports/Operasi/FrmBarChartPDF', $this->data, true);
		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function saveChart()
	{

		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$imageData = file_get_contents("php://input");
			$decodedImage = base64_decode($imageData);
			$savePath = FCPATH . '/images/chart_image.png';
			file_put_contents($savePath, $decodedImage);
			echo "Chart image saved successfully!";
		} else {
			header('HTTP/1.1 405 Method Not Allowed');
			echo "Method Not Allowed";
		}
	}



	public function AttributeKontrak()
	{
		$this->load->Model('m_report');

		$data['judul'] = 'Print Attribute Kontrak ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmAttributeKontrak", $data);
		$this->load->view('templates/footer');
	}



	public function MonitoringRencanaTermin()
	{
		$this->load->Model('m_report');

		$data['judul'] = 'Monitoring Rencana Termin ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmMonitoringRencanaTermin", $data);
		$this->load->view('templates/footer');
	}

	public function RptRPPM()
	{
		$this->load->Model('m_report');

		$PeriodeRPPM = $this->m_report->GetPeriodeRPPM();
		$data['Periode'] = $PeriodeRPPM;

		$data['judul'] = 'Monitoring RPPM ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmRptRPPM", $data);
		$this->load->view('templates/footer');
	}

	public function KurvaProgress($JobNo = '')
	{
		$this->load->Model('m_report');

		$data['bruto'] = '';
		$data['JobNoChart'] = '';


		if (isset($_POST['JobNoChart'])) {
			$JobNo = $this->input->post('JobNoChart');
			$data['JobNo'] = $JobNo;

			//DataCurva
			$query = $this->db->query("Report_GraphCurva N'{$JobNo}'");
			$data['result'] = $this->db->query("Report_GraphCurva N'{$JobNo}'")->result();

			//history KO
			$data['historyko'] = $this->db->query("Report_HistoryKO N'{$JobNo}'")->result();



			$query_kontrak = "SELECT * FROM Job WHERE JobNo='$JobNo' ";
			$ekse_kontrak = $this->db->query($query_kontrak)->row();
			$data['sumber_dana_detail'] = $ekse_kontrak->SumberDana;
		}


		$query_tampil_job_no = "SELECT JobNo, JobNm FROM Job WHERE TipeJob='Project' AND Company='$this->Company' or Company='KIP' or Company='DLL' ";
		$ekse_tampil_job_no = $this->db->query($query_tampil_job_no);


		$data['tampil_job_no'] = $ekse_tampil_job_no;

		$data['Tender'] = $this->db->query("Report_Tender")->result();



		$data['judul'] = 'Kurva Progress ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/frmSCurve1", $data);
		$this->load->view('templates/footer');
	}

	public function PKET()
	{
		$this->load->Model('m_report');

		$data['Tahun'] = '';

		if (isset($_POST['Tahun'])) {
			$Thn = $this->input->post('Tahun');
			$data['tahun'] = $Thn;

			$RPKET = $this->m_report->GetPKET($Thn);
			$data['Rekap'] = $RPKET;
		}

		$data['judul'] = 'Rekap Proyek: Kontrak, Expense & Termin ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Operasi/FrmRPKET", $data);
		$this->load->view('templates/footer');
	}

	public function getDataTermin()
	{
		$this->session->unset_userdata('exportExcel');
		$startDate = $this->input->post('startDate');
		$endDate = $this->input->post('endDate');
		$statusJob = $this->input->post('StatusJob');
		$getJob = $this->db->query("select StatusJob, JobNo, JobNm, KSO, Own, TipeManajerial From Job WHERE StatusJob = '$statusJob' AND Company = '$this->Company'")->result();
		$JOIntegrated = [];
		$SumJOIntegrated = [];
		$JOSplit = [];
		$SumJOSplit = [];
		$JOPartial = [];
		$SumJOPartial = [];
		$JOSingle = [];
		$SumJOSingle = [];
		foreach ($getJob as $row) {
			if ($row->TipeManajerial == 'JO Partial') {
				$getTermin = $this->db->query("select * from TerminInduk where JobNo = '$row->JobNo' and TglCair BETWEEN '$startDate' AND '$endDate' ")->result();
				$getSum = $this->db->query("
							SELECT JobNo, COUNT(JobNo) AS JumlahJobNo, SUM(BrutoBOQ) AS TotalBrutoBOQ
							FROM TerminInduk
							WHERE JobNo = '$row->JobNo' AND TglCair BETWEEN '$startDate' AND '$endDate'
							GROUP BY JobNo;")->result();
				foreach ($getSum as $row2) {
					$SumJOPartial[] = [
						'JobNo' => $row2->JobNo,
						'Sum' => $row2->TotalBrutoBOQ,
						'JmlJobNo' => $row2->JumlahJobNo
					];
				}
				foreach ($getTermin as $row2) {
					$JOPartial[] = [
						'StatusJob' => $row->StatusJob,
						'TipeManajerial' => $row->TipeManajerial,
						'JobNo' => $row->JobNo,
						'JobNm' => $row->JobNm,
						'TglTermin' => tgl_baru($row2->TglCair),
						'NilaiBruto' => $row2->BrutoBOQ,
						'DPP' => ($row2->BrutoBOQ / (1 + ($row2->PPN_persen / 100))),
						'PPN' => ($row2->BrutoBOQ * ($row2->PPN_persen / 100)),
						'PPH' => ($row2->BrutoBOQ * ($row2->PPH_persen / 100)),
						'Netto' => $row2->NettoBOQ,
					];
				}
			} else if ($row->TipeManajerial == 'JO Integrated') {
				// get data from table TerminInduk
				$getTermin = $this->db->query("select * from TerminInduk where JobNo = '$row->JobNo' and TglCair BETWEEN '$startDate' AND '$endDate' ")->result();
				$getSum = $this->db->query("
							SELECT JobNo, COUNT(JobNo) AS JumlahJobNo, SUM(BrutoBOQ) AS TotalBrutoBOQ
							FROM TerminInduk
							WHERE JobNo = '$row->JobNo' AND TglCair BETWEEN '$startDate' AND '$endDate'
							GROUP BY JobNo;")->result();
				foreach ($getSum as $row2) {
					$SumJOIntegrated[] = [
						'JobNo' => $row2->JobNo,
						'Sum' => $row2->TotalBrutoBOQ,
						'JmlJobNo' => $row2->JumlahJobNo
					];
				}
				foreach ($getTermin as $row2) {
					$JOIntegrated[] = [
						'StatusJob' => $row->StatusJob,
						'TipeManajerial' => $row->TipeManajerial,
						'JobNo' => $row->JobNo,
						'JobNm' => $row->JobNm,
						'TglTermin' => tgl_baru($row2->TglCair),
						'NilaiBruto' => $row2->BrutoBOQ,
						'DPP' => ($row2->BrutoBOQ / (1 + ($row2->PPN_persen / 100))),
						'PPN' => ($row2->BrutoBOQ * ($row2->PPN_persen / 100)),
						'PPH' => ($row2->BrutoBOQ * ($row2->PPH_persen / 100)),
						'Netto' => $row2->NettoBOQ,
					];
				}
			} else if ($row->TipeManajerial == '') {
				if ($row->KSO == 1) {
					$getTermin = $this->db->query("select * from TerminInduk where JobNo = '$row->JobNo' and tglCair BETWEEN '$startDate' AND '$endDate' ")->result();
					$getSum = $this->db->query("
							SELECT JobNo, COUNT(JobNo) AS JumlahJobNo, SUM(BrutoBOQ) AS TotalBrutoBOQ
							FROM TerminInduk
							WHERE JobNo = '$row->JobNo' AND TglCair BETWEEN '$startDate' AND '$endDate'
							GROUP BY JobNo;")->result();
					foreach ($getSum as $row2) {
						$SumJOSplit[] = [
							'JobNo' => $row2->JobNo,
							'Sum' => $row2->TotalBrutoBOQ,
							'JmlJobNo' => $row2->JumlahJobNo
						];
					}
					foreach ($getTermin as $row2) {
						$JOSplit[] = [
							'StatusJob' => $row->StatusJob,
							'TipeManajerial' => $row->TipeManajerial,
							'JobNo' => $row->JobNo,
							'JobNm' => $row->JobNm,
							'TglTermin' => tgl_baru($row2->TglCair),
							'NilaiBruto' => $row2->BrutoBOQ,
							'DPP' => ($row2->BrutoBOQ / (1 + ($row2->PPN_persen / 100))),
							'PPN' => ($row2->BrutoBOQ * ($row2->PPN_persen / 100)),
							'PPH' => ($row2->BrutoBOQ * ($row2->PPH_persen / 100)),
							'Netto' => $row2->NettoBOQ,
						];
					}
				} else if ($row->KSO == 0) {
					$getTermin = $this->db->query("select * from TerminInduk where JobNo = '$row->JobNo' and tglCair BETWEEN '$startDate' AND '$endDate' ")->result();
					$getSum = $this->db->query("
							SELECT JobNo, COUNT(JobNo) AS JumlahJobNo, SUM(BrutoBOQ) AS TotalBrutoBOQ
							FROM TerminInduk
							WHERE JobNo = '$row->JobNo' AND TglCair BETWEEN '$startDate' AND '$endDate'
							GROUP BY JobNo;")->result();
					foreach ($getSum as $row2) {
						$SumJOSplit[] = [
							'JobNo' => $row2->JobNo,
							'Sum' => $row2->TotalBrutoBOQ,
							'JmlJobNo' => $row2->JumlahJobNo
						];
					}
					foreach ($getTermin as $row2) {
						$JOSplit[] = [
							'StatusJob' => $row->StatusJob,
							'TipeManajerial' => $row->TipeManajerial,
							'JobNo' => $row->JobNo,
							'JobNm' => $row->JobNm,
							'TglTermin' => tgl_baru($row2->TglCair),
							'NilaiBruto' => $row2->BrutoBOQ,
							'DPP' => ($row2->BrutoBOQ / (1 + ($row2->PPN_persen / 100))),
							'PPN' => ($row2->BrutoBOQ * ($row2->PPN_persen / 100)),
							'PPH' => ($row2->BrutoBOQ * ($row2->PPH_persen / 100)),
							'Netto' => $row2->NettoBOQ,
						];
					}
				} else {
					continue;
				}
			} else if ($row->TipeManajerial == 'Single') {
				if ($row->Own == ' ') {
					$getTermin = $this->db->query("select * from TerminInduk where JobNo = '$row->JobNo' and tglCair BETWEEN '$startDate' AND '$endDate' ")->result();
					$getSum = $this->db->query("
							SELECT JobNo, COUNT(JobNo) AS JumlahJobNo, SUM(BrutoBOQ) AS TotalBrutoBOQ
							FROM TerminInduk
							WHERE JobNo = '$row->JobNo' AND TglCair BETWEEN '$startDate' AND '$endDate'
							GROUP BY JobNo;")->result();
					foreach ($getSum as $row2) {
						$SumJOSingle[] = [
							'JobNo' => $row2->JobNo,
							'Sum' => $row2->TotalBrutoBOQ,
							'JmlJobNo' => $row2->JumlahJobNo
						];
					}
					foreach ($getTermin as $row2) {
						$JOSingle[] = [
							'StatusJob' => $row->StatusJob,
							'TipeManajerial' => $row->TipeManajerial,
							'JobNo' => $row->JobNo,
							'JobNm' => $row->JobNm,
							'TglTermin' => tgl_baru($row2->TglCair),
							'NilaiBruto' => $row2->BrutoBOQ,
							'DPP' => ($row2->BrutoBOQ / (1 + ($row2->PPN_persen / 100))),
							'PPN' => ($row2->BrutoBOQ * ($row2->PPN_persen / 100)),
							'PPH' => ($row2->BrutoBOQ * ($row2->PPH_persen / 100)),
							'Netto' => $row2->NettoBOQ,
						];
					}
				} else if ($row->Own == 2) {
					$getTermin = $this->db->query("select * from TerminMember where JobNo = '$row->JobNo' and tglCair BETWEEN '$startDate' AND '$endDate' ")->result();
					foreach ($getTermin as $row2) {
						$JOSingle[] = [
							'StatusJob' => $row->StatusJob,
							'TipeManajerial' => $row->TipeManajerial,
							'JobNo' => $row->JobNo,
							'JobNm' => $row->JobNm,
							'TglTermin' => tgl_baru($row2->TglCair),
							'NilaiBruto' => $row2->BrutoBOQ,
							'DPP' => ($row2->BrutoBOQ / (1 + ($row2->PPN_persen / 100))),
							'PPN' => ($row2->BrutoBOQ * ($row2->PPN_persen / 100)),
							'PPH' => ($row2->BrutoBOQ * ($row2->PPH_persen / 100)),
							'Netto' => $row2->NettoBOQ,
						];
					}
				} else {
					continue;
				}
			}
		}
		$data = [
			'JOIntegrated' => $JOIntegrated,
			'SumJOIntegrated' => $SumJOIntegrated,
			'JOSplit' => $JOSplit,
			'SumJOSplit' => $SumJOSplit,
			'JOPartial' => $JOPartial,
			'SumJOPartial' => $SumJOPartial,
			'JOSingle' => $JOSingle,
			'SumJOSingle' => $SumJOSingle,
			'hasil' => $getJob
		];
		$this->session->set_userdata('exportExcel', $data);
		echo json_encode($data);
	}

	public function exportExcel()
	{
		$dataSession = $this->session->userdata('exportExcel');
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$styleHeader = 	[
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
			'font' => ['bold' => true],
		];
		$style_border_center = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];
		$style_border_right = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'indent' => 1,
			],
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];
		$style_border_left = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'indent' => 1,
			],
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];
		$textColor = 'FFFFFF';
		$styleTextColor = [
			'color' => ['rgb' => $textColor],
		];

		$sheet->mergeCells('A1:I1');
		$sheet->setCellValue('A1', 'DATA MONITORING TERMIN ' . $this->Company);
		$sheet->getStyle('A1')->applyFromArray([
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
			'font' => ['bold' => true],
		]);
		$sheet->getStyle('A1')->getFont()->setSize(18);
		$sheet->mergeCells('A3:I3');
		$sheet->setCellValue('A3', 'Data Manajerial: JO Integrated');
		$sheet->setCellValue('A4', "No");
		$sheet->setCellValue('B4', "JobNo");
		$sheet->setCellValue('C4', "Job Nama");
		$sheet->setCellValue('D4', "Tanggal Termin");
		$sheet->setCellValue('E4', "Netto");
		$sheet->setCellValue('F4', "DPP");
		$sheet->setCellValue('G4', "PPN");
		$sheet->setCellValue('H4', "PPH");
		$sheet->setCellValue('I4', "Nilai Bruto");
		$sheet->getStyle('A4')->applyFromArray($styleHeader);
		$sheet->getStyle('B4')->applyFromArray($styleHeader);
		$sheet->getStyle('C4')->applyFromArray($styleHeader);
		$sheet->getStyle('D4')->applyFromArray($styleHeader);
		$sheet->getStyle('E4')->applyFromArray($styleHeader);
		$sheet->getStyle('F4')->applyFromArray($styleHeader);
		$sheet->getStyle('G4')->applyFromArray($styleHeader);
		$sheet->getStyle('H4')->applyFromArray($styleHeader);
		$sheet->getStyle('I4')->applyFromArray($styleHeader);

		$sheet->getStyle('A3')->applyFromArray($styleHeader);
		$sheet->getStyle('A3')->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => '2166DE'],
			],
		]);
		$sheet->getStyle('A3')->getFont()->applyFromArray($styleTextColor);
		$sheet->getStyle('I4')->applyFromArray($styleHeader);
		$sheet->getStyle('A4:I4')->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'A4CC83'],
			],
		]);

		$no = 1;
		$numrow = 5;
		$prevJobNo = null;
		foreach ($dataSession['JOIntegrated'] as $row) {
			if ($prevJobNo != null) {
				if ($prevJobNo != $row['JobNo']) {
					foreach ($dataSession['SumJOIntegrated'] as $row2) {
						if ($prevJobNo == $row2['JobNo']) {
							$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
								'fill' => [
									'fillType' => Fill::FILL_SOLID,
									'startColor' => ['rgb' => '8bbcfc'],
								],
							]);
							$sheet->setCellValue('A' . $numrow, 'TOTAL');
							$sheet->setCellValue('I' . $numrow, $row2['Sum']);
							$numrow += 1;
						}
					}
				}
			}
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row['JobNo']);
			$sheet->setCellValue('C' . $numrow, $row['JobNm']);
			$sheet->setCellValue('D' . $numrow, $row['TglTermin']);
			$sheet->setCellValue('E' . $numrow, ($row['Netto'] == null ? 0 : $row['Netto']));
			$sheet->setCellValue('F' . $numrow, ($row['DPP'] == null ? 0 : $row['DPP']));
			$sheet->setCellValue('G' . $numrow, ($row['PPN'] == null ? 0 : $row['PPN']));
			$sheet->setCellValue('H' . $numrow, ($row['PPH'] == null ? 0 : $row['PPH']));
			$sheet->setCellValue('I' . $numrow, $row['NilaiBruto']);
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_border_left);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_border_right);
			$no++;
			$numrow++;
			$prevJobNo = $row['JobNo'];
		}
		if ($prevJobNo !== null) {
			foreach ($dataSession['SumJOIntegrated'] as $row2) {
				if ($prevJobNo == $row2['JobNo']) {
					$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
						'fill' => [
							'fillType' => Fill::FILL_SOLID,
							'startColor' => ['rgb' => '8bbcfc'],
						],
					]);
					$sheet->setCellValue('A' . $numrow, 'TOTAL');
					$sheet->setCellValue('I' . $numrow, $row2['Sum']);
				}
			}
		}
		$numrow += 3;
		$sheet->mergeCells('A' . $numrow . ':I' . $numrow);
		$sheet->setCellValue('A' . $numrow, 'Data Manajerial: JO Split');
		$sheet->getStyle('A' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('A' . $numrow)->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => '2166DE'],
			],
		]);
		$sheet->getStyle('A' . $numrow)->getFont()->applyFromArray($styleTextColor);
		$numrow += 1;
		$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'A4CC83'],
			],
		]);
		$sheet->setCellValue('A' . $numrow, 'Data Manajerial: JO Integrated');
		$sheet->setCellValue('A' . $numrow, "No");
		$sheet->setCellValue('B' . $numrow, "JobNo");
		$sheet->setCellValue('C' . $numrow, "Job Nama");
		$sheet->setCellValue('D' . $numrow, "Tanggal Termin");
		$sheet->setCellValue('E' . $numrow, "Netto");
		$sheet->setCellValue('F' . $numrow, "DPP");
		$sheet->setCellValue('G' . $numrow, "PPN");
		$sheet->setCellValue('H' . $numrow, "PPH");
		$sheet->setCellValue('I' . $numrow, "Nilai Bruto");
		$sheet->getStyle('A' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('B' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('C' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('D' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('E' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('F' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('G' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('H' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('I' . $numrow)->applyFromArray($styleHeader);
		$no = 1;
		$numrow += 1;
		$prevJobNo = null;
		foreach ($dataSession['JOSplit'] as $row) {
			if ($prevJobNo != null) {
				if ($prevJobNo != $row['JobNo']) {
					foreach ($dataSession['SumJOSplit'] as $row2) {
						if ($prevJobNo == $row2['JobNo']) {
							$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
								'fill' => [
									'fillType' => Fill::FILL_SOLID,
									'startColor' => ['rgb' => '8bbcfc'],
								],
							]);
							$sheet->setCellValue('A' . $numrow, 'TOTAL');
							$sheet->setCellValue('I' . $numrow, $row2['Sum']);
							$numrow += 1;
						}
					}
				}
			}
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row['JobNo']);
			$sheet->setCellValue('C' . $numrow, $row['JobNm']);
			$sheet->setCellValue('D' . $numrow, $row['TglTermin']);
			$sheet->setCellValue('E' . $numrow, ($row['Netto'] == null ? 0 : $row['Netto']));
			$sheet->setCellValue('F' . $numrow, ($row['DPP'] == null ? 0 : $row['DPP']));
			$sheet->setCellValue('G' . $numrow, ($row['PPN'] == null ? 0 : $row['PPN']));
			$sheet->setCellValue('H' . $numrow, ($row['PPH'] == null ? 0 : $row['PPH']));
			$sheet->setCellValue('I' . $numrow, $row['NilaiBruto']);
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_border_left);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_border_right);
			$no++;
			$numrow++;
			$prevJobNo = $row['JobNo'];
		}
		if ($prevJobNo !== null) {
			foreach ($dataSession['SumJOSplit'] as $row2) {
				if ($prevJobNo == $row2['JobNo']) {
					$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
						'fill' => [
							'fillType' => Fill::FILL_SOLID,
							'startColor' => ['rgb' => '8bbcfc'],
						],
					]);
					$sheet->setCellValue('A' . $numrow, 'TOTAL');
					$sheet->setCellValue('I' . $numrow, $row2['Sum']);
				}
			}
		}
		$numrow += 3;
		$sheet->mergeCells('A' . $numrow . ':I' . $numrow);
		$sheet->setCellValue('A' . $numrow, 'Data Manajerial: JO Single');
		$sheet->getStyle('A' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('A' . $numrow)->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => '2166DE'],
			],
		]);
		$sheet->getStyle('A' . $numrow)->getFont()->applyFromArray($styleTextColor);
		$numrow += 1;
		$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'A4CC83'],
			],
		]);
		$sheet->setCellValue('A' . $numrow, 'Data Manajerial: JO Integrated');
		$sheet->setCellValue('A' . $numrow, "No");
		$sheet->setCellValue('B' . $numrow, "JobNo");
		$sheet->setCellValue('C' . $numrow, "Job Nama");
		$sheet->setCellValue('D' . $numrow, "Tanggal Termin");
		$sheet->setCellValue('E' . $numrow, "Netto");
		$sheet->setCellValue('F' . $numrow, "DPP");
		$sheet->setCellValue('G' . $numrow, "PPN");
		$sheet->setCellValue('H' . $numrow, "PPH");
		$sheet->setCellValue('I' . $numrow, "Nilai Bruto");
		$sheet->getStyle('A' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('B' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('C' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('D' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('E' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('F' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('G' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('H' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('I' . $numrow)->applyFromArray($styleHeader);
		$no = 1;
		$numrow += 1;
		$prevJobNo = null;
		foreach ($dataSession['JOSingle'] as $row) {
			if ($prevJobNo != null) {
				if ($prevJobNo != $row['JobNo']) {
					foreach ($dataSession['SumJOSingle'] as $row2) {
						if ($prevJobNo == $row2['JobNo']) {
							$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
								'fill' => [
									'fillType' => Fill::FILL_SOLID,
									'startColor' => ['rgb' => '8bbcfc'],
								],
							]);
							$sheet->setCellValue('A' . $numrow, 'TOTAL');
							$sheet->setCellValue('I' . $numrow, $row2['Sum']);
							$numrow += 1;
						}
					}
				}
			}
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row['JobNo']);
			$sheet->setCellValue('C' . $numrow, $row['JobNm']);
			$sheet->setCellValue('D' . $numrow, $row['TglTermin']);
			$sheet->setCellValue('E' . $numrow, ($row['Netto'] == null ? 0 : $row['Netto']));
			$sheet->setCellValue('F' . $numrow, ($row['DPP'] == null ? 0 : $row['DPP']));
			$sheet->setCellValue('G' . $numrow, ($row['PPN'] == null ? 0 : $row['PPN']));
			$sheet->setCellValue('H' . $numrow, ($row['PPH'] == null ? 0 : $row['PPH']));
			$sheet->setCellValue('I' . $numrow, $row['NilaiBruto']);
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_border_left);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_border_right);
			$no++;
			$numrow++;
			$prevJobNo = $row['JobNo'];
		}
		if ($prevJobNo !== null) {
			foreach ($dataSession['SumJOSingle'] as $row2) {
				if ($prevJobNo == $row2['JobNo']) {
					$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
						'fill' => [
							'fillType' => Fill::FILL_SOLID,
							'startColor' => ['rgb' => '8bbcfc'],
						],
					]);
					$sheet->setCellValue('A' . $numrow, 'TOTAL');
					$sheet->setCellValue('I' . $numrow, $row2['Sum']);
				}
			}
		}
		$numrow += 3;
		$sheet->mergeCells('A' . $numrow . ':I' . $numrow);
		$sheet->setCellValue('A' . $numrow, 'Data Manajerial: JO Partial');
		$sheet->getStyle('A' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('A' . $numrow)->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => '2166DE'],
			],
		]);
		$sheet->getStyle('A' . $numrow)->getFont()->applyFromArray($styleTextColor);
		$numrow += 1;
		$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'A4CC83'],
			],
		]);
		$sheet->setCellValue('A' . $numrow, 'Data Manajerial: JO Integrated');
		$sheet->setCellValue('A' . $numrow, "No");
		$sheet->setCellValue('B' . $numrow, "JobNo");
		$sheet->setCellValue('C' . $numrow, "Job Nama");
		$sheet->setCellValue('D' . $numrow, "Tanggal Termin");
		$sheet->setCellValue('E' . $numrow, "Netto");
		$sheet->setCellValue('F' . $numrow, "DPP");
		$sheet->setCellValue('G' . $numrow, "PPN");
		$sheet->setCellValue('H' . $numrow, "PPH");
		$sheet->setCellValue('I' . $numrow, "Nilai Bruto");
		$sheet->getStyle('A' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('B' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('C' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('D' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('E' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('F' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('G' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('H' . $numrow)->applyFromArray($styleHeader);
		$sheet->getStyle('I' . $numrow)->applyFromArray($styleHeader);
		$no = 1;
		$numrow += 1;
		$prevJobNo = null;
		foreach ($dataSession['JOPartial'] as $row) {
			if ($prevJobNo != null) {
				if ($prevJobNo != $row['JobNo']) {
					foreach ($dataSession['SumJOPartial'] as $row2) {
						if ($prevJobNo == $row2['JobNo']) {
							$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
							$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
								'fill' => [
									'fillType' => Fill::FILL_SOLID,
									'startColor' => ['rgb' => '8bbcfc'],
								],
							]);
							$sheet->setCellValue('A' . $numrow, 'TOTAL');
							$sheet->setCellValue('I' . $numrow, $row2['Sum']);
							$numrow += 1;
						}
					}
				}
			}
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row['JobNo']);
			$sheet->setCellValue('C' . $numrow, $row['JobNm']);
			$sheet->setCellValue('D' . $numrow, $row['TglTermin']);
			$sheet->setCellValue('E' . $numrow, ($row['Netto'] == null ? 0 : $row['Netto']));
			$sheet->setCellValue('F' . $numrow, ($row['DPP'] == null ? 0 : $row['DPP']));
			$sheet->setCellValue('G' . $numrow, ($row['PPN'] == null ? 0 : $row['PPN']));
			$sheet->setCellValue('H' . $numrow, ($row['PPH'] == null ? 0 : $row['PPH']));
			$sheet->setCellValue('I' . $numrow, $row['NilaiBruto']);
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_border_left);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_border_right);
			$no++;
			$numrow++;
			$prevJobNo = $row['JobNo'];
		}
		if ($prevJobNo !== null) {
			foreach ($dataSession['SumJOPartial'] as $row2) {
				if ($prevJobNo == $row2['JobNo']) {
					$sheet->mergeCells('A' . $numrow . ':H' . $numrow);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray($styleHeader);
					$sheet->getStyle('A' . $numrow . ':I' . $numrow)->applyFromArray([
						'fill' => [
							'fillType' => Fill::FILL_SOLID,
							'startColor' => ['rgb' => '8bbcfc'],
						],
					]);
					$sheet->setCellValue('A' . $numrow, 'TOTAL');
					$sheet->setCellValue('I' . $numrow, $row2['Sum']);
				}
			}
		}

		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(8);
		$sheet->getColumnDimension('C')->setWidth(50);
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->getColumnDimension('E')->setWidth(16);
		$sheet->getColumnDimension('F')->setWidth(16);
		$sheet->getColumnDimension('G')->setWidth(16);
		$sheet->getColumnDimension('H')->setWidth(16);
		$sheet->getColumnDimension('I')->setWidth(18);

		$sheet->getStyle('E:E')->getNumberFormat()->setFormatCode('#,##0');
		$sheet->getStyle('F:F')->getNumberFormat()->setFormatCode('#,##0');
		$sheet->getStyle('G:G')->getNumberFormat()->setFormatCode('#,##0');
		$sheet->getStyle('H:H')->getNumberFormat()->setFormatCode('#,##0');
		$sheet->getStyle('I:I')->getNumberFormat()->setFormatCode('#,##0');

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("Monitoring Termin");
		// die;
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="monitoring_termin.xlsx"');
		header('Cache-Control: max-age=0');
		// exit;
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
