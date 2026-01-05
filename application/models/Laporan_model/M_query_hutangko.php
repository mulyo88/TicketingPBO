<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_query_hutangko extends CI_Model
{


	function getJSON_QqueryHutangKO($JobNo, $VendorId, $Status)
	{

		$this->QuerySementara_HutangKO($JobNo);


		$this->load->library('Datatables');

		$Company = $this->config->item('Company');


		$this->datatables->select("
			LedgerNo, JobNo, JobNm, NoKO, TglKO, KategoriId, VendorId, VendorNm, TotalKO, PaymentKO, RemainingKO, TotalInv, PaymentInv, RemainingInv, Company");
		$this->datatables->from('#queryHutangKO_table');
		$this->datatables->where('Company', $Company);

		if ($VendorId <> 'ALL') {
			$this->datatables->where('VendorId', $VendorId);
		}

		if ($Status <> 'ALL') {

			if ($Status == 'Outstanding_KO') {

				$this->datatables->where('RemainingKO > ', '0');
			} else if ($Status == 'Outstanding_Invoice') {

				$this->datatables->where('RemainingInv > ', '0');
			} else if ($Status == 'Settle_KO') {

				$this->datatables->where('RemainingKO <= ', '0');
			} else if ($Status == 'Settle_Invoice') {

				$this->datatables->where('RemainingInv <=', '0');
			}
		}

		// $this->datatables->add_column('dataDetail','<button type="button" class="btn btn-sm btn-info dataDetail" data-LedgerNo="$1">','LedgerNo');

		$this->datatables->add_column('dataDetail', '<a href="javascript:void(0)" class="btn btn-sm btn-info btn_detail" title="Lihat Detail" onclick="LihatDetail(\'$1\',\'$2\')" >Detail</a>', 'JobNo,NoKO');

		$this->datatables->edit_column('TglKO', '$1', 'TanggalSaya(TglKO)');

		$this->datatables->edit_column('TotalKO', '$1', 'new_decimal(TotalKO)');
		$this->datatables->edit_column('PaymentKO', '$1', 'new_decimal(PaymentKO)');
		$this->datatables->edit_column('RemainingKO', '$1', 'new_decimal(RemainingKO)');


		$this->datatables->edit_column('TotalInv', '$1', 'new_decimal(TotalInv)');
		$this->datatables->edit_column('PaymentInv', '$1', 'new_decimal(PaymentInv)');
		$this->datatables->edit_column('RemainingInv', '$1', 'new_decimal(RemainingInv)');

		return $this->datatables->generate();
	}



	private function QuerySementara_HutangKO($JobNo)
	{

		$Company = $this->config->item('Company');

		$this->db->query("
			DROP TABLE IF EXISTS #queryHutangKO_table
			CREATE TABLE #queryHutangKO_table (
			LedgerNo bigint NOT NULL IDENTITY (1,1),
			JobNo nvarchar(15),
			JobNm nvarchar(100),
			NoKO nvarchar(15),
			TglKO date,
			KategoriId nvarchar(30),
			VendorId nvarchar(7),
			VendorNm nvarchar(100),
			TotalKO money,
			PaymentKO money,
			RemainingKO money,
			TotalInv money,
			PaymentInv money,
			RemainingInv money,
			Company nvarchar(10),
			);


			INSERT INTO #queryHutangKO_table (JobNo, JobNm, NoKO, TglKO, KategoriId, VendorId, VendorNm, TotalKO, PaymentKO, RemainingKO, TotalInv, PaymentInv, RemainingInv, Company)
			SELECT  
			A.JobNo AS JobNo, 
			D.JobNm,
			A.NoKO, 
			A.TglKO, 
			A.KategoriId,
			A.VendorId, 
			B.VendorNm, 
			A.SubTotal-A.DiscAmount+A.PPN AS TotalKO,
			ISNULL((SELECT SUM(Amount) FROM BLE WHERE NoKO=A.NoKO),0) AS PaymentKO,
			(CASE 
			WHEN A.ClosedBy IS NULL THEN
			A.SubTotal-A.DiscAmount+A.PPN - ISNULL((SELECT SUM(AMOUNT) FROM BLE WHERE NoKO=A.NoKO),0)
			ELSE 0 
			END) AS 'RemainingKO',
			ISNULL((SELECT SUM(Total) FROM Invoice WHERE NoKO=A.NoKO),0) AS TotalInv,
			ISNULL((SELECT SUM(PaymentAmount) 
			FROM BLE, InvPD, PdHdr 
			WHERE
			(BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=A.NoKO AND InvPD.NoPD=PdHdr.NoPD),0) AS PaymentInv, 
			(CASE 
			WHEN A.ClosedBy IS NULL THEN 
			ISNULL(ISNULL((SELECT SUM(Total) FROM Invoice WHERE NoKO=A.NoKO),0) - ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr WHERE 
			(BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=A.NoKO AND InvPD.NoPD=PdHdr.NoPD),0),0) 
			ELSE 
			0 
			END) AS 'RemainingInv',
			D.Company

			FROM KoHdr A 
			LEFT JOIN Vendor B ON B.VendorId=A.VendorId
			LEFT JOIN Job D  ON D.JobNo=A.JobNo 
			WHERE A.ApprovedBy IS NOT NULL AND A.CanceledBy IS NULL AND D.Company='$Company' AND A.JobNo='$JobNo'
		");
	}

	function QueryDetailPembayaran($JobNo)
	{
		$Company = $this->config->item('Company');

		$query = $this->db->query("SELECT 
						X.JobNo, 
						X.JobNm, 
						X.NoKO, 
						X.TglKO, 
						X.KategoriId, 
						X.VendorId, 
						X.VendorNm, 
						X.TotalKO, 
						X.PaymentKO, 
						X.RemainingKO, 
						X.TotalInv, 
						X.PaymentInv, 
						X.RemainingInv, 
						X.Company, 
						B.TglBayar
					FROM
					(
						SELECT  
							A.JobNo AS JobNo, D.JobNm, A.NoKO, A.TglKO, A.KategoriId, A.VendorId, B.VendorNm, A.SubTotal - A.DiscAmount + A.PPN AS TotalKO, 
							ISNULL(( SELECT SUM(Amount) FROM BLE WHERE NoKO=A.NoKO),0) AS PaymentKO,
								(
									CASE 
										WHEN A.ClosedBy IS NULL THEN A.SubTotal-A.DiscAmount+A.PPN - ISNULL((SELECT SUM(AMOUNT) FROM BLE WHERE NoKO=A.NoKO),0)
										ELSE 0 
									END
								) AS 'RemainingKO',
								ISNULL((SELECT SUM(Total) FROM Invoice WHERE NoKO=A.NoKO),0) AS TotalInv,
								ISNULL((SELECT SUM(PaymentAmount)
								FROM BLE, InvPD, PdHdr 
								WHERE
								(BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=A.NoKO AND InvPD.NoPD=PdHdr.NoPD),0) AS PaymentInv, 
								(CASE 
								WHEN A.ClosedBy IS NULL THEN 
								ISNULL(ISNULL((SELECT SUM(Total) FROM Invoice WHERE NoKO=A.NoKO),0) - ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr WHERE 
								(BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=A.NoKO AND InvPD.NoPD=PdHdr.NoPD),0),0) 
								ELSE 
								0 
								END
							) AS 'RemainingInv',
							D.Company

						FROM KoHdr A 
						LEFT JOIN Vendor B ON B.VendorId=A.VendorId
						LEFT JOIN Job D  ON D.JobNo=A.JobNo 
						WHERE A.ApprovedBy IS NOT NULL AND A.CanceledBy IS NULL AND D.Company='$Company' AND A.JobNo='$JobNo'
					) AS X

					LEFT OUTER JOIN PdHdr AS A ON X.NoKO = A.NoKO
					LEFT OUTER JOIN BLE AS B ON A.NoPD = B.NoPD AND A.NoKO = B.NoKO
					GROUP BY X.JobNo, X.JobNm, X.NoKO, X.TglKO, X.KategoriId, X.VendorId, X.VendorNm, X.TotalKO, X.PaymentKO, X.RemainingKO, X.TotalInv, X.PaymentInv, X.RemainingInv, X.Company, B.TglBayar");

		return $query->result();


	}


	function QueryPDF($JobNo, $VendorId, $Status)
	{
		$this->QuerySementara_HutangKO($JobNo);
		$Company = $this->config->item('Company');


		$this->db->select(" *");
		$this->db->from('#queryHutangKO_table');
		$this->db->where('Company', $Company);

		if ($VendorId <> 'ALL') {
			$this->db->where('VendorId', $VendorId);
		}

		if ($Status <> 'ALL') {

			if ($Status == 'Outstanding_KO') {

				$this->db->where('RemainingKO > ', '0');
			} else if ($Status == 'Outstanding_Invoice') {

				$this->db->where('RemainingInv > ', '0');
			} else if ($Status == 'Settle_KO') {

				$this->db->where('RemainingKO <= ', '0');
			} else if ($Status == 'Settle_Invoice') {

				$this->db->where('RemainingInv <=', '0');
			}
		}
		// $this->db->limit(3);

		return $this->db->get()->result_array();
	}
}

/* End of file M_query_hutangko.php */
/* Location: ./application/models/Laporan_model/M_query_hutangko.php */
