<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_progress_fisik extends CI_Model {

	function get_list_data_json_PF($JobNo){
		$this->load->library('Datatables');


		$this->datatables->select("LedgerNo, Tahun, Bulan, RencanaK, RealisasiK, RealisasiKeuK, RencanaTB, RealisasiTB, RealisasiKeuTB, JobNo");
		$this->datatables->from('Progress');
		$this->datatables->where('JobNo',$JobNo);

		$this->datatables->add_column('aksi', '
			<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="dapat_form_ubah_PF($1,$2)" data-JobNo="$1" data-LedgerNo="$2" title="Lihat / Edit">SELECT</a>', 'JobNo,LedgerNo');

		// $this->datatables->edit_column('Tahun', '$1', '$this->Tahun');
		$this->datatables->edit_column('Bulan', '$1', '$this->getBulan(Bulan)');

		$this->datatables->edit_column('RencanaK', '$1', 'new_decimal(RencanaK)');
		$this->datatables->edit_column('RealisasiK', '$1', 'new_decimal(RealisasiK)');
		$this->datatables->edit_column('RealisasiKeuK', '$1', 'new_decimal(RealisasiKeuK)');
		$this->datatables->edit_column('RencanaTB', '$1', 'new_decimal(RencanaTB)');
		$this->datatables->edit_column('RealisasiTB', '$1', 'new_decimal(RealisasiTB)');
		$this->datatables->edit_column('RealisasiKeuTB', '$1', 'new_decimal(RealisasiKeuTB)');
			return $this->datatables->generate();
	}

	function aksi_simpan_PF($data){
		$this->db->trans_begin();

		$this->db->insert('Progress',$data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
	}

	function get_data_PF($JobNo,$LedgerNo){
		$where = array(
			'JobNo' => $JobNo,
			'LedgerNo' => $LedgerNo
		);

		$query = $this->db->where($where)->get('Progress');
		return $query;
	}

	function aksi_ubah_PF($data,$where){
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update('Progress');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
	}

	


}

/* End of file M_progress_fisik.php */
/* Location: ./application/models/Menu_Entry_model/M_progress_fisik.php */
