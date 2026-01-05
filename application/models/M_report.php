<?php

class M_report extends CI_Model
{
	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');

		
	}

	public function get_ko($JobNo = null)
	{
		$query = $this->db->get_where('KoHdr', array('JobNo' => $JobNo))->row();
		return $query;
	}

	function getData($RekId,$NoRek,$Periode1='',$Periode2=''){

		$where="";
		if ($Periode1 !='' AND $Periode2 !='' ) {
			$where = " WHERE tglbayar BETWEEN  '$Periode1' AND '$Periode2' ";
		}
		
		$this->db->query("

			--set nocount on;
			--set arithabort on;

			if OBJECT_ID('tempdb..#tempA') IS NOT NULL DROP TABLE #tempA
			create table #tempA(
			id bigint identity(1,1), tglbayar datetime, ledgerno numeric(18,0), keterangan text, debet money, kredit money, saldo money
			)
			if OBJECT_ID('tempdb..#tempB') IS NOT NULL DROP TABLE #tempB
			create table #tempB(
			id bigint identity(1,1), tglbayar datetime, ledgerno numeric(18,0), keterangan text, debet money, kredit money, saldo money
			)

			insert into #tempA
			select nx.TglBayar, nx.ledgerno, replace(nx.keterangan,' -  -  - ','') as keterangan, nx.debet, nx.kredit, nx.saldo from
			(select mx.TglBayar, mx.ledgerno,
			case
			when not mx.NoPD = '' then CONCAT(mx.NoPD, ' - ', mx.CaraBayar, ' - ', mx.NoRek, ' - ', mx.AtasNama)
			else CONCAT(mx.Keterangan, ' - ', mx.CaraBayar, ' - ', mx.NoRek, ' - ', mx.AtasNama)
			end as keterangan,
			mx.debet, mx.kredit, 0 as saldo
			from
			(
			select lx.ledgerno, lx.TglBayar, lx.nopd, lx.JobNo, lx.Keterangan, lx.Alokasi, lx.RekId, lx.CaraBayar, lx.NoRek, lx.AtasNama, lx.bank, 
			case
			when lx.[type] = 'debet' then lx.Amount
			else 0
			end as debet,
			case
			when lx.[type] = 'kredit' then lx.Amount
			else 0
			end as kredit
			, lx.[type] from
			(select dx.ledgerno, dx.TglBayar, dx.nopd, dx.JobNo, dx.Keterangan, dx.Alokasi, dx.RekId, dx.CaraBayar, dx.NoRek, dx.AtasNama, dx.bank, dx.Amount, 'debet' as [type] from
			(select ledgerno, TglBayar, nopd, JobNo, Keterangan, Alokasi, RekId, CaraBayar, NoRek, AtasNama, bank, Amount from [dbo].[BLE] where rekid = '$RekId') as dx
			union all
			select kx.ledgerno, kx.TglBayar, kx.nopd, kx.JobNo, kx.Keterangan, kx.Alokasi, kx.RekId, kx.CaraBayar, kx.NoRek, kx.AtasNama, kx.bank, kx.Amount, 'kredit' as [type] from
			(select bx.ledgerno, ax.TglBayar, bx.nopd, ax.JobNo, ax.Keterangan, ax.Alokasi, ax.RekId, ax.CaraBayar, bx.NoRek, ax.AtasNama, ax.bank, ax.Amount from
			(select * from [dbo].[BLE]) as ax
			inner join
			(select c.ledgerno, c.nopd, c.NoRek from
			(select b.ledgerno, b.nopd,replace(b.NoRek,'.','') as NoRek from
			(select a.ledgerno, a.nopd,replace(a.NoRek,'-','') as NoRek from
			(select ledgerno, nopd, replace(NoRek,' ','') as NoRek from [dbo].[BLE]) as a)
			as b) 
			as c
			where c.NoRek = '$NoRek')
			as bx
			on ax.LedgerNo = bx.LedgerNo 
			) as kx
			) as lx
			) as mx
			) as nx order by nx.TglBayar, nx.LedgerNo

			declare @counter int
			declare @maxid int
			declare @saldo int
			set @saldo = 0
			select @counter = min(id), @maxid = max(id) from #tempA
			while (@counter is not null and @counter <= @maxid)
			begin
			select @saldo = @saldo - debet + kredit from #tempA where id = @counter

			insert into #tempB
			select tglbayar, ledgerno, keterangan, debet, kredit, @saldo from #tempA where id = @counter

			SET @counter  = @counter  + 1
			end

			");

		return $this->db->query("select * from #tempB ".$where." ")->result();
	}

	function QueryPiutangProgressFisik()
	{
		return $query = $this->db->query("WITH TmpQuery AS (
			SELECT JobNo, JobNm, Kategori, 
			(SELECT TOP 1 Tgl2 FROM RPPM WHERE JobNo=Job.JobNo ORDER BY Tgl2 DESC) AS 'DateRPPM', 
			(SELECT TOP 1 ISNULL(Induk,0) FROM RPPM WHERE JobNo=Job.JobNo ORDER BY Tgl2 DESC) AS 'PersenRPPM', 
			(SELECT TOP 1 ISNULL(Induk,0)/100 * (Job.Bruto/1.1) FROM RPPM WHERE JobNo=Job.JobNo ORDER BY Tgl2 DESC) AS 'RpRPPM', 
			(SELECT ISNULL(SUM(BrutoBOQ/1.1),0) FROM TerminInduk WHERE JobNo=Job.JobNo AND Jenis='Termin') AS 'RpTermin', 
			(SELECT ISNULL((SUM(BrutoBOQ/1.1)/(Job.Bruto/1.1))*100,0) FROM TerminInduk WHERE JobNo=Job.JobNo AND Jenis='Termin') AS 'PersenTermin' 
			FROM Job WHERE TipeJob='Project' AND StatusJob='Pelaksanaan' AND Company='$this->Company') 
			SELECT *, PersenRPPM-PersenTermin AS 'SisaPersen', RpRPPM-RpTermin AS 'SisaRp' FROM TmpQuery ORDER BY JobNo")->result();
	}

	function getDataTermin($JobNo = '')
	{
		return $this->db->query("SELECT a.JobNo,a.JobNm,a.KSO,a.Own,a.SumberDana,
			(CASE 
				WHEN KSO=0 AND Own=''
				THEN 
				(SELECT SUM(TerminInduk) as apa FROM TerminInduk WHERE JobNo=a.JobNo GROUP BY JobNo )
				WHEN KSO=1 AND Own=1
				THEN (SELECT SUM(TerminMember1) as apa FROM TerminMember WHERE JobNo=a.JobNo GROUP BY JobNo )
				WHEN KSO=1 AND Own=2
				THEN (SELECT SUM(TerminMember2) as apa FROM TerminMember WHERE JobNo=a.JobNo GROUP BY JobNo )
				END) as 'TotalTermin'
			FROM Job a WHERE a.JobNo='$JobNo'");
	}

	function GetPeriodeRPPM($tahun = '')
	{
		$query = "SELECT DISTINCT Tgl1, Tgl2 FROM RPPM WHERE YEAR(Tgl1)= '2022' ORDER BY Tgl1";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	
	}

	function GetJob()
	{
		return $this->db->query("SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND Company='$this->Company'")->result();
	}

	function GetPKET($tahun)
	{
		return $this->db->query("SELECT JobNo, JobNm, Netto, PersenKSO, ISNULL(Netto*(PersenKSO/100),0) AS 'NettoShare', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$tahun' AND TglPD<='$tahun' AND PdHdr.Alokasi='A') AS 'AlokasiA', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$tahun' AND TglPD<='$tahun' AND PdHdr.Alokasi='B') AS 'AlokasiB', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$tahun' AND TglPD<='$tahun' AND PdHdr.Alokasi='C') AS 'AlokasiC', 
			(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) AS 'TerminInduk', 
			(CASE 
				WHEN Job.KSO='1' AND Job.TipeManajerial='JO Partial' THEN 
			(CASE 
				WHEN Job.Own='1' THEN 
			(SELECT ISNULL(SUM(TerminMember1 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
			WHEN Job.Own='2' THEN 
			(SELECT ISNULL(SUM(TerminMember2 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
			END) ELSE (SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) END) AS 'TerminMember' 
			FROM Job WHERE TipeJob='Project' AND StatusJob='Pelaksanaan' AND Job.Company='$this->Company' ORDER BY JobNo")->result();
	}
}
