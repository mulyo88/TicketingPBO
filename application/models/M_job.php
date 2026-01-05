<?php

class M_job extends CI_Model
{

	var $Company;

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		
	}

	public function GetJob_By_User()
	{
		$UserID=$this->session->userdata('MIS_LOGGED_ID');
		$query = "select ax.JobNo, bx.JobNm from
					(select item as JobNo
					from
					dbo.SplitString ((select top 1 a.AksesJob from (select * from Login) as a
					left outer join
					(select * from Job) as b
					on b.JobNo = a.AksesJob
					Where a.UserID= '$UserID'), ',')) as ax
					left outer join 
					Job as bx
					on bx.JobNo = ax.JobNo group by ax.JobNo, bx.JobNm";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}
    public function data_job()
    {
        $query = $this->db->query("SELECT * From Job WHERE Company='$this->Company' AND TipeJob='Project' AND StatusJob='Pelaksanaan' Or StatusJob='Pemeliharaan' ORDER BY JobNo DESC");
        return $query;
    }

	public function SimpanData($table, $data)
	{
		$this->db->insert($table, $data);
	}

    public function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    public function backsub($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    //Cluster DATA PROYEK 

    public function dataproyek($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    public function UpdateDataProyek($table, $data, $where)
    {
         $this->db->update($table, $data, $where);
    }

    //end Data Proyek

    public function datakontrak($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();        
        return $query;
    }

	public function UpdateDataKontrak($JobNo=null)
	{
		$this->db->query("select CeklistLapangan from CeklistDok where JobNo = $JobNo and id = (select max(id) from CeklistDok where JobNo = $JobNo)");
		// $this->db->update($table, $data, $where);
	}

	public function dataAddendum($JobNo = null)
	{
		$query = $this->db->query("SELECT TOP 1 * FROM Job WHERE JobNo='$JobNo'")->result_object();
		return $query;
	}

	public function ceklistDoc($JobNo = null)
	{
		return $this->db->select('*')
				->from('CeklistDok')
				->where('JobNo', $JobNo)
				->get()->row_array();
	}

	public function addendum($JobNo=null)
	{
		$query = $this->db->order_by('TimeEntry','DESC')->get_where('JobH', array('JobNo' => $JobNo))->result();
        return $query;
	}

	public function updateDRP($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
	}

    public function JaminanKontrak($JobNo = null)
    {
        $query = $this->db->get_where('JaminanKontrak', array('JobNo' => $JobNo))->result_object();
        return $query;
    }

    public function JaminanPelaksanaan($JobNo = null, $NamaJaminan = null)
    {
        $query = $this->db->query("SELECT * FROM JaminanKontrak WHERE JobNo=$JobNo AND NamaJaminan ='Jaminan Pelaksanaan'")->result_object();
        return $query;
    }

    public function JaminanUangMuka($JobNo = null, $NamaJaminan = null)
    {
        $query = $this->db->query("SELECT * FROM JaminanKontrak WHERE JobNo=$JobNo AND NamaJaminan ='Jaminan Uang Muka'")->result_object();
        return $query;
    }

    public function JaminanSisPel($JobNo = null, $NamaJaminan = null)
    {
        $query = $this->db->query("SELECT * FROM JaminanKontrak WHERE JobNo=$JobNo AND NamaJaminan ='Jaminan Sisa Pelaksanaan'")->result_object();
        return $query;
    }

    public function JaminanPemeliharaan($JobNo = null, $NamaJaminan = null)
    {
        $query = $this->db->query("SELECT * FROM JaminanKontrak WHERE JobNo=$JobNo AND NamaJaminan ='Jaminan Pemeliharaan'")->result_object();
        return $query;
    }

    public function simpan_JaminanKontrak($table, $data)
    {
        $this->db->insert($table,$data);
    }

    public function updateFHO($where,$table,$data)
    {
        $this->db->update($table, $data, $where);
         //return $this->db->get_where($table,$where);
    }

	//CLuster DIPA TERMIN

    public function dipa($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }
    public function TambahDipa($table, $data)
    {
        $this->db->insert($table, $data);
    }

    public function tbldipa($JobNo)
    {
        $query = "SELECT * FROM DIPA WHERE JobNo='$JobNo'";
        $eksekusi = $this->db->query($query);
        return $eksekusi->result();
    }

	// public function SumDipaBruto($JobNo)
	// {
	// 	$this->db->select_sum('Budget');
	// 	$this->db->from('Dipa');
	// 	$this->db->where('JobNo', $JobNo);
	// 	return $this->db->get('')->row();
	// }

	public function SumBrutoJob($JobNo)
	{
		$this->db->select('Bruto');
		$this->db->from('Job');
		$this->db->where('JobNo', $JobNo);
		return $this->db->get('')->row();
	}

	public function SumDipaPaguBudget($JobNo)
	{
		$this->db->select_sum('PaguBudget');
		$this->db->from('Dipa');
		$this->db->where('JobNo', $JobNo);
		return $this->db->get('')->row();
	}

    public function DeleteDipa($where, $table)
    {
        // $this->db->where('id_Dipa', $id_Dipa);
        // $this->db->delete('DIPA');
        $this->db->where($where);
        $this->db->delete($table);
    }

	public function getRtermin ($JobNo = null)
	{
		$query = "SELECT * FROM RencanaTermin WHERE JobNo='$JobNo'";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

	public function getBrutoTermin($JobNo = null)
	{
		$query = "SELECT * FROM Job WHERE JobNo='$JobNo'";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}


	public function hapus_data($where, $table)
	{;
		$this->db->where($where);
		$this->db->delete($table);
	}

	public function GetBruto($JobNo)
	{
		$query = "SELECT TOP 1 Persentase FROM RencanaTermin WHERE JobNo='$JobNo'";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

	public function SumRTBruto($JobNo)
	{
		$this->db->select_sum('Bruto');
		$this->db->from('RencanaTermin');
		$this->db->where('JobNo', $JobNo);
		return $this->db->get('')->row();
	}
	public function SumRTNetto($JobNo)
	{
		$this->db->select_sum('Netto');
		$this->db->from('RencanaTermin');
		$this->db->where('JobNo', $JobNo);
		return $this->db->get('')->row();
	}

	public function getTblTerminInduk($JobNo = null)
	{
		$query = "SELECT * FROM TerminInduk WHERE JobNo='$JobNo' ORDER BY LedgerNo DESC  ";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

	public function getTblTerminMember($JobNo = null)
	{
		$query = "SELECT * FROM TerminMember WHERE JobNo='$JobNo' ORDER BY LedgerNo DESC  ";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

    public function tatakelola($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

	public function GetTataKelola($JobNo=null)
	{
		$query = "SELECT * FROM Job WHERE JobNo='$JobNo'";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

	public function GetMpp($JobNo = null)
	{
		$query = "SELECT * FROM MPP WHERE JobNo='$JobNo'";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

    public function rap($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

	public function GetAlokasi()
	{
		// "SELECT * FROM Alokasi";

		$UserID=$this->session->userdata('MIS_LOGGED_ID');
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
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}	

	// public function GetTipeForm($Alokasi)
	// {
	// 	$query = "SELECT TipeForm.TipeForm, tipeForm.Keterangan From Alokasi Inner Join TipeForm On TipeForm.Alokasi = Alokasi.Alokasi Where Alokasi.Alokasi='$Alokasi'";
	// 	$eksekusi = $this->db->query($query);
	// 	return $eksekusi->result();
	// }

	

    public function pdpj($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    public function rppm($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    public function mos($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    public function spr($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    public function tambahspr($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    public function leaflet($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

    public function ProgressFisik($JobNo = null)
    {
        $query = $this->db->get_where('Job', array('JobNo' => $JobNo))->row();
        return $query;
    }

	public function checkProjectFieldTeam($JobNo = null)
    {
		$query="select item from
		dbo.SplitString(
		(select CeklistLapangan from CeklistDoc where JobNo = '$JobNo' and id = (select max(id) from CeklistDoc where JobNo = '$JobNo'))
		, ',')";
		return $this->db->query($query)->result_object();		
	}

	public function checkProjectPCTeam($JobNo = null)
    {
		$query="select item  from
		dbo.SplitString(
		(select CeklistPC from CeklistDoc where JobNo = '$JobNo' and id = (select max(id) from CeklistDoc where JobNo = '$JobNo'))
		, ',')";
		return $this->db->query($query)->result_object();		
	}

	public function checkPHO1($JobNo = null)
    {
		$query="select item from
		dbo.SplitString(
		(select chkdokpho1 from job where JobNo = '$JobNo')
		, ',')";
		return $this->db->query($query)->result_object();		
	}

	public function checkPHO2($JobNo = null)
    {
		$query="select item from
		dbo.SplitString(
		(select chkdokpho2 from job where JobNo = '$JobNo')
		, ',')";
		return $this->db->query($query)->result_object();		
	}

	// function GetYAD()
	// {
	// 	$query = "SELECT * From YAD";
	// 	return $this->db->query($query)->result();
	// }
	public function TblYAD($JobNo=null)
	{
		$query = "SELECT * FROM YAD WHERE JobNo='$JobNo' ORDER BY LedgerNo DESC  ";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

	function	getTipeForm($Alokasi)
	{
		$query = $this->db->query("SELECT * FROM TipeForm WHERE Alokasi = '$Alokasi' ORDER BY Alokasi ASC");
		return $query->result();
	}

	function HapusYAD($LedgerNo)
	{
		$this->db->trans_start();
		$this->db->where('LedgerNo', $LedgerNo)->delete('YAD');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function kodevendor(){
		$this->db->select('MAX(RIGHT(Vendor.VendorID, 4)) as VendorId', FALSE);
		$this->db->order_by('VendorID', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('Vendor');
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = intval($data->VendorId) + 1;
		} else {
			$kode = 1;
		}
		$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);
		$kodetampil = "VEN" . $batas;
		return $kodetampil; 
	}

	function getVendor()
	{
		$query = $this->db->query("SELECT * FROM Vendor ORDER BY VendorId DESC");
		return $query;
	}

	public function checkSP($NoKO = null){
	// { return $this->db->query("SELECT SyaratPembayaran FROM KoHdr Where NoKO='$NoKO'")->result();


		// $query = "select item from
		// dbo.SplitString(
		// (select SyaratPembayaran from KoHdr where NoKO = '$NoKO') 
		// , ',')";
		// return $this->db->query($query)->result_object();

		$query = "select 
		case
			when a.item = 'INV' then 'Invoice/Kwitansi'
			when a.item = 'SJ' then 'Surat Jalan/Tanda Terima Lapangan'
			when a.item = 'PO' then 'Copy PO'
			when a.item = 'FP' then 'Faktur Pajak'
			when a.item = 'BAP' then 'Berita Acara Pembayaran'
			when a.item = 'BAOP' then 'Berita Acara Opname Pekerjaan'
		end as item
		from 
		(select item from
				dbo.SplitString(
				(select SyaratPembayaran from KoHdr where NoKO = '$NoKO') 
				, ',')
		) as a";

		return $this->db->query($query)->result_object();
	}


	function UpdateAddendum($data,$JobNo){
		$this->db->trans_begin();
		$UserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry	= date("Y-m-d H:i:s");

       	$job_pindah_ke_jobh = $this->db->query("
       		INSERT INTO JobH (JobNo,Bruto,Netto,PaguBruto,PaguNetto,NoKontrak,TglKontrak,AddendumKe,RemarkAddendum,PrdAwal,PrdAkhir,Hari,UserEntry,TimeEntry)
			SELECT JobNo,
			Bruto,
			Netto,
			PaguBruto,
			PaguNetto,
			NoKontrak,
			TglKontrak,
			AddendumKe,
			RemarkAddendum,
			PrdAwal,
			PrdAkhir,
			Hari,
			'$UserEntry',
			'$TimeEntry'
			FROM Job WHERE JobNo='$JobNo'

       		");

       	$ubah_data_job = $this->db->set($data)->where('JobNo',$JobNo)->update('Job');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }

	}

	

	

}
