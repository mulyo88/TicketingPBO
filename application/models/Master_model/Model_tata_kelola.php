<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tata_kelola extends CI_Model {

	function dapatkan_data_job($JobNo){

		$query_saya = "SELECT *,

		(CASE
			WHEN SumberDana LIKE '%LOAN%' THEN
			CASE
			WHEN KSO='1' AND Own=1 THEN (Bruto*PersenShare1)/100
			WHEN KSO='1' AND Own=2 THEN (Bruto*PersenShare2)/100
			WHEN KSO='0' THEN Bruto
			END
			ELSE 
			CASE
			WHEN KSO='1' AND Own=1 THEN (Netto*PersenShare1)/100
			WHEN KSO='1' AND Own=2 THEN (Netto*PersenShare2)/100
			WHEN KSO='0' THEN Netto
			END
			END ) AS 'NilaiKontrak'
		FROM Job WHERE JobNo='$JobNo'  ORDER BY JobNo DESC ";
		$eksekusi = $this->db->query($query_saya);

		return $eksekusi;

		// $query_saya = "SELECT *,

		// (CASE
		// 	WHEN SumberDana LIKE '%LOAN%' THEN
		// 	CASE
		// 	WHEN KSO='1' AND Own=1 THEN (Bruto*PersenShare1)/100
		// 	WHEN KSO='1' AND Own=2 THEN (Bruto*PersenShare2)/100
		// 	WHEN KSO='0' THEN Bruto
		// 	END
		// 	ELSE 
		// 	CASE
		// 	WHEN KSO='1' AND Own=1 THEN (Netto*PersenShare1)/100
		// 	WHEN KSO='1' AND Own=2 THEN (Netto*PersenShare2)/100
		// 	WHEN KSO='0' THEN Netto
		// 	END
		// 	END ) AS 'NilaiKontrak'
		// FROM Job WHERE (Company='MDH' OR Company='KIP' OR Company='DLL' ) WHERE Job  ORDER BY JobNo DESC ";

		
		

		// $query = $this->db->where('JobNo',$JobNo)->get('Job');
		
		// return $eksekusi;
	}	

	function update_tatakelola($JobNo,$set_array,$data_gabungan,$KSO){
		$this->db->trans_begin();

		if ($KSO == 0) {
			$this->db->set($set_array)->where('JobNo',$JobNo)->update('Job');
		}

		if($KSO > 0){
			$this->db->set($data_gabungan)->where('JobNo',$JobNo)->update('Job');
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

}

/* End of file Model_tata_kelola.php */
/* Location: ./application/models/Master_model/Model_tata_kelola.php */