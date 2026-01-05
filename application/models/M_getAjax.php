<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_rppm extends CI_Model
{

	public function Get_json_YAD($JobNo = '')
	{

		$this->datatables->select('
			LedgerNo,
			Alokasi,
			TipeForm,
			Tgl,
			Amount,
			Remark
			
			');
		$this->datatables->from('YAD');
		if ($JobNo != '') {
			$this->datatables->where('JobNo', $JobNo);
		}
		$this->datatables->add_column('view', '<a onclick="EditAppraisal($1)" data-toggle="tooltip" data-placement="top" class="btn btn-md btn-warning waves-effect waves-light"><i class="fa fa-pencil" title="Edit Data"></i> Edit</a> <button onclick="hapus($1)" type="button" class="btn btn-md btn-danger waves-effect waves-light"><i class="fa fa-trash"></i> Hapus</button>', 'id_appraisal');
		$this->db->order_by('id_appraisal', 'DESC');
		$this->datatables->edit_column('NilaiAppraisal', '$1', 'GetFormatNumber(NilaiAppraisal)');
		$this->datatables->edit_column('EstimasiPasar', '$1', 'GetFormatNumber(EstimasiPasar)');
		$this->datatables->edit_column('HargaPerolehan', '$1', 'GetFormatNumber(HargaPerolehan)');
		$this->datatables->edit_column('Estimasi', '$1', 'CekEstimasi(Estimasi)');

		return $this->datatables->generate();
	}

	function getYAD($LedgerNo)
	{
		return $this->db->where('LedgerNo', $LedgerNo)->get('YAD')->row();
	}

  
}

/* End of file M_rppm_model.php */
/* Location: ./application/models/M_rppm_model.php */
