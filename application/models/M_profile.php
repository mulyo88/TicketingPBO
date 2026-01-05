<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_profile extends CI_Model {

	var $NIK=NULL;

	function __construct()
	{
		parent::__construct();
		$this->NIK = $this->session->userdata('MIS_LOGGED_NIK');
	}
	

	function getDataKaryawan(){

		$NIK = $this->NIK;
		$query = $this->db->query(" SELECT * FROM Karyawan WHERE NIK='$NIK' ");
		return $query;

	}

	function getData($JenisID=''){

		$NIK = $this->NIK;

		$Jenis='';
		if ($JenisID <> '') {
			$Jenis = "AND  JenisID='$JenisID' ";
		}

		$query = $this->db->query("
			SELECT 
			a.NIK,
			a.JenisID,
			a.NoID,
			a.PrdAwal,
			a.PrdAkhir,
			a.DiterbitkanOleh,
			a.UserEntry,
			a.TimeEntry,
			a.DiterbitkanOleh
			FROM 
			EmpID a
			WHERE a.NIK='$NIK' ".$Jenis."

			");
		return $query;


	}


	// menu identitas personal
	function getIdentitasPersonal(){
		$NIK = $this->NIK;
		$query = $this->db->query("
			SELECT 
			a.NIK,
			a.JenisID,
			a.NoID,
			a.PrdAwal,
			a.PrdAkhir,
			a.DiterbitkanOleh,
			a.UserEntry,
			a.TimeEntry,
			a.DiterbitkanOleh
			FROM 
			EmpID a
			WHERE a.NIK='$NIK' 

			");
		return $query->result_array();
		

	}

	function updateKaryawan($dataKTP, $dataPASSPOR, $data_NPWP, $data_KK, $data_SIM_A, $data_SIM_B, $data_SIM_C, $NIK){
		
		

		$this->db->trans_start();
		// $NIK = $this->NIK;

		$whereKTP = array(
			'NIK' => $NIK,
			'JenisID' => 'KTP'
		);

		$cekKTP = $this->db->get_where('EmpID',$whereKTP)->num_rows();
		if ($cekKTP > 0) {
			$query_update_KTP = $this->db->set($dataKTP)->where($whereKTP)->update('EmpID');
		}else{
			$dataKTP['NIK'] = $NIK; 
			$querySimpanKTP = $this->db->insert('EmpID',$dataKTP);
		}


		$wherePASSPOR = array(
			'NIK' => $NIK,
			'JenisID' => 'PASSPOR'
		);

		$cekPASSPOR = $this->db->get_where('EmpID',$wherePASSPOR)->num_rows();
		if ($cekPASSPOR > 0) {
			$query_update_PASSPOR = $this->db->set($dataPASSPOR)->where($wherePASSPOR)->update('EmpID');
		}else{
			$dataPASSPOR['NIK'] = $NIK; 
			$querySimpanPASSPOR = $this->db->insert('EmpID',$dataPASSPOR);
		}


		$where_NPWP = array(
			'NIK' => $NIK,
			'JenisID' => 'NPWP'
		);

		$cek_NPWP = $this->db->get_where('EmpID',$where_NPWP)->num_rows();
		if ($cek_NPWP > 0) {
			$query_update_NPWP = $this->db->set($data_NPWP)->where($where_NPWP)->update('EmpID');
		}else{
			$data_NPWP['NIK'] = $NIK; 
			$querySimpan_NPWP = $this->db->insert('EmpID',$data_NPWP);
		}


		$where_KK = array(
			'NIK' => $NIK,
			'JenisID' => 'KK'
		);

		$cek_KK = $this->db->get_where('EmpID',$where_KK)->num_rows();
		if ($cek_KK > 0) {
			$query_update_KK = $this->db->set($data_KK)->where($where_KK)->update('EmpID');
		}else{
			$data_KK['NIK'] = $NIK; 
			$querySimpan_KK = $this->db->insert('EmpID',$data_KK);
		}


		$where_SIM_A = array(
			'NIK' => $NIK,
			'JenisID' => 'SIM_A'
		);

		$cek_SIM_A = $this->db->get_where('EmpID',$where_SIM_A)->num_rows();
		if ($cek_SIM_A > 0) {
			$query_update_SIM_A = $this->db->set($data_SIM_A)->where($where_SIM_A)->update('EmpID');
		}else{
			$data_SIM_A['NIK'] = $NIK; 
			$querySimpan_SIM_A = $this->db->insert('EmpID',$data_SIM_A);
		}


		$where_SIM_B = array(
			'NIK' => $NIK,
			'JenisID' => 'SIM_B'
		);

		$cek_SIM_B = $this->db->get_where('EmpID',$where_SIM_B)->num_rows();
		if ($cek_SIM_B > 0) {
			$query_update_SIM_B = $this->db->set($data_SIM_B)->where($where_SIM_B)->update('EmpID');
		}else{
			$data_SIM_B['NIK'] = $NIK; 
			$querySimpan_SIM_B = $this->db->insert('EmpID',$data_SIM_B);
		}

		$where_SIM_C = array(
			'NIK' => $NIK,
			'JenisID' => 'SIM_C'
		);

		$cek_SIM_C = $this->db->get_where('EmpID',$where_SIM_C)->num_rows();
		if ($cek_SIM_C > 0) {
			$query_update_SIM_C = $this->db->set($data_SIM_C)->where($where_SIM_C)->update('EmpID');
		}else{
			$data_SIM_C['NIK'] = $NIK; 
			$querySimpan_SIM_C = $this->db->insert('EmpID',$data_SIM_C);
		}




		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			// $this->db->trans_rollback();
			return false;
		}
		else
		{
			// $this->db->trans_commit();
			return true;
		}
	}
	// menu akhir identitas personal


	// menu data pribadi

	function dapatDataPribadi(){

		$NIK = $this->NIK;

		$query = $this->db->get_where('Karyawan',array('NIK'=>$NIK));
		return $query;
	}

	function aksi_update_data_pribadi($NIK, $data, $NamaFile){

		$this->db->trans_start();

		$this->db->set($data);
		$this->db->where('NIK',$NIK);
		$this->db->update('Karyawan');

		$this->ganti_foto($NIK,$NamaFile);


		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	//menu akhir data pribadi


	//awal data keluarga
	function getKeluarga(){
		
		$NIK = $this->NIK;
		$query = $this->db->order_by('NoUrutKeluarga','ASC')->get_where('EmpKeluarga',array('NIK' => $NIK ))->result_array();
		return $query;
	}

	function saveDataKeluarga($data){

		$this->db->trans_start();

		$this->db->insert('EmpKeluarga',$data);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}

	}

	function HapusDataKeluarga($data){

		$this->db->trans_start();

		$this->db->where($data)->delete('EmpKeluarga');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}

	}

	function dapatDataKeluarga($data){
		$myquery = $this->db->get_where('EmpKeluarga',$data)->row_array();
		return $myquery;
	}

	function Query_UpdateKeluarga($set,$where,$table){

		$this->db->trans_start();

		$this->db->set($set)->where($where)->update($table);


		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}

	}


	//akhir data keluarga


	/// awal Emergency Contact

	function dapatData_EmergencyContact($NIK){
		$query = $this->db->where('NIK',$NIK)->get('EmpEmergency');
		return $query->result_array();
	}

	function AksiSimpan_EC($data,$where){
		$this->db->trans_start();

		$this->db->insert('EmpEmergency',$data);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function AksiHapus_EC($where){
		$this->db->trans_start();

		$this->db->where($where)->delete('EmpEmergency');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function aksiUpdate_EC($set,$where){
		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('EmpEmergency');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}


	function dapat_dataPendidikan($NIK){
		$query = $this->db->where('NIK',$NIK)->order_by('NoUrutPendidikan','ASC')->get('EmpPendidikan');
		return $query->result_array();
	}

	// --------------------------- batas -------------------

	// --data pendidikan---

	function aksi_SimpanPendidikan($data){
		$this->db->trans_start();

		$this->db->insert('EmpPendidikan',$data);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function aksi_HapusPendidikan($where){
		$this->db->trans_start();

		$this->db->where($where)->delete('EmpPendidikan');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function aksi_UpdatePendidikan($set,$where){
		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('EmpPendidikan');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	// --akhir pendidikan--

	// ---------------------------------batas-------------

	// awal keterampilan
	function dapat_data_ketrampilan($NIK){
		$query = $this->db->where('NIK',$NIK)->order_by('NoUrutKetrampilan','ASC')->get('EmpKetrampilan');
		return $query->result_array();
	}

	function aksi_simpan_ketrampilan($array_data){
		$this->db->trans_start();

		$this->db->insert('EmpKetrampilan',$array_data);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function aksi_hapus_ketrampilan($where){
		$this->db->trans_start();

		$this->db->where($where)->delete('EmpKetrampilan');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function aksi_update_ketrampilan($set,$where){
		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('EmpKetrampilan');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}


	//akhir keterampilan



	// ------------------------------------batas-------------------

	// --- Riwayat Pekerjaan
	function dapat_data_riwayat_pekerjaan($NIK){
		$myquery = $this->db->where('NIK',$NIK)->get('EmpPekerjaanH');
		return $myquery->result_array();
	}

	function aksi_simpan_riwayat_pekerjaan($data){
		$this->db->trans_start();

		$this->db->insert('EmpPekerjaanH',$data);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function aksi_hapus_riwayat_pekerjaan($where){
		$this->db->trans_start();

		$this->db->where($where)->delete('EmpPekerjaanH');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	function aksi_update_riwayat_pekerjaan($set,$where){
		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('EmpPekerjaanH');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}

	//akhir riwayat pekerjaan


	// ------------batas---------------

	//awal posisi pekerjaan

	function dapat_data_posisi_pekerjaan($NIK){
		$myquery = $this->db->where('NIK',$NIK)->get('EmpPekerjaanMinarta');
		return $myquery->result_array();
	}

	function get_company($NIK){
		$myquery = $this->db->query("SELECT DISTINCT(Company) AS 'CompanyKaryawan' FROM KontrakKerja WHERE NIK='$NIK'");

		if ($myquery->num_rows() == 0 ) {
			return NULL;
		}else{
			$row = $myquery->row_array();
			return $row['CompanyKaryawan'];
		}
	}

	function get_list_jabatan($Company){
		$myquery = $this->db->query(" SELECT ID_JobPosition, PositionName FROM JobPosition WHERE Company='$Company' ")->result_array();
		return $myquery;
	}

	function get_list_jabatan_tanpa_company(){
		$myquery = $this->db->query(" SELECT ID_JobPosition, PositionName FROM JobPosition  ")->result_array();
		return $myquery;
	}

	function dapat_data_karyawan($NIK){
		$myquery = $this->db->select('*')->where('NIK',$NIK)->get('Karyawan')->row_array();
		return $myquery;
	}

	function aksi_update_posisi_pekerjaan($set,$where){
		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('Karyawan');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}
	}



	//akhir posisi pekerjaan

	// ------batas-----

	// ---awal payroll--

	function get_payroll($NIK){
		
		$myquery = $this->db
		->select('a.*, 
			(a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional ) AS "GajiBruto", 
			(a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional - a.PPh21 - a.JHTEmployee - a.JPEmployee ) AS "GajiNetto"  
			')
		->where('a.NIK',$NIK)
		->get('EmpPayroll a')->row_array();
		return $myquery;
	}

	// ----akhir payroll



	// ---batas--
	private function ganti_foto($NIK,$NamaFile=''){

		$this->db->trans_start();


		if ($NamaFile !='') {

			$data_foto = array(
				'NIK' => $NIK,
				'NamaFile' => $NamaFile,
				'TipeFile' => 'Foto',
				'StatusFoto' => 'Pribadi',
				'UserEntry' => $this->session->userdata("MIS_LOGGED_NAME"),
				'TimeEntry' => date('Y-m-d H:i:s'),
			);

			$cek_foto = $this->db->query(" SELECT * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND TipeFile='Foto' AND StatusFoto='Pribadi'  ");

			$cek_jumlah = $cek_foto->num_rows();
			$row_data = $cek_foto->row_array();
			$NamaFoto = $row_data['NamaFile'];
			$LedgerNo = $row_data['LedgerNo'];

			if ($cek_jumlah == 0) {
				$simpan_foto = $this->db->insert('tbl_dokumen_pendukung',$data_foto);
				
			}else{
				$set = array(
					'NamaFile' => $NamaFile,
					'TipeFile' => 'Foto',
					'StatusFoto' => 'Pribadi',
					'UserEntry' => $this->session->userdata("MIS_LOGGED_NAME"),
					'TimeEntry' => date('Y-m-d H:i:s'),
				);

				$where = array('NIK' => $NIK, 'LedgerNo' => $LedgerNo, 'StatusFoto' => 'Pribadi' );

				$letak_direktori = url_utk_document_root_dan_nama_folder('local');
				if (file_exists($letak_direktori.$NamaFoto)) {
					unlink($letak_direktori.$NamaFoto);
				}


				// if (file_exists('assets/foto_karyawan/'.$NamaFoto)) {
				// 	unlink('assets/foto_karyawan/'.$NamaFoto);
				// }
				
				$update_foto = $this->db->set($set)->where($where)->update('tbl_dokumen_pendukung');

			}


		}


		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {

			// $this->db->trans_rollback();
			return FALSE;
		} else {

			// $this->db->trans_commit();
			return TRUE;
		}




	}


}

/* End of file M_profile.php */
/* Location: ./application/models/M_profile.php */