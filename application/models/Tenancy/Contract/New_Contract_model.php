<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_Contract_model extends CI_Model
{
    protected $table = 'tnc_c_new_contract';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get($this->table)
                        ->row();
    }

    public function get_series($code, $date)
    {
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));

        $query = $this->db->
            query("SELECT COUNT(*) AS total FROM tnc_c_new_contract WHERE series_code = '$code' AND YEAR(date_trans) = $year AND MONTH(date_trans) = $month
        ")->row();

        return $query;
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)
                        ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)
                        ->delete($this->table);
    }

    public function get_all_builder()
    {
        $query = $this->db->
            query("SELECT
                A.id, A.series, A.date_trans, B.code, B.owner, B.brand, B.product
            FROM
                tnc_c_new_contract AS A
            LEFT OUTER JOIN
                tnc_tn_tenant AS B ON A.tenant_id = B.id
            ")->result();

        return $query;
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // belongsTo
    public function tenant($id) {
        $data = $this->find($id);
        if ($data) {
            $this->load->model('Tenant_model');
            return $this->Tenant_model->find($data->tenant_id);
        }
        return null;
    }

    public function building($id) {
        $data = $this->find($id);
        if ($data) {
            $this->load->model('Building_model');
            return $this->Building_model->find($data->building_id);
        }
        return null;
    }

    public function bank($bank_id) {
        $this->load->model('Bank_model');
        return $this->Bank_model->find($bank_id);
    }

    // hasMany
    public function contract_detail($contract_id) {
        $this->load->model('New_Contract_Detail_model');
        return $this->New_Contract_Detail_model->get_by_contract_id($contract_id);
    }

    public function contract_detail_edit($contract_id) {
        $this->load->model('New_Contract_Detail_model');
        return $this->New_Contract_Detail_model->get_all_query($contract_id);
    }
}
