<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_Contract_Detail_model extends CI_Model
{
    protected $table = 'tnc_c_new_contract_detail';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/MasterData/Property/Unit_model');
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

    public function delete_by_contract_id($contract_id)
    {
        return $this->db->where('contract_id', $contract_id)
                        ->delete($this->table);
    }

    public function get_by_contract_id($contract_id) {
        return $this->db->get_where($this->table, ['contract_id' => $contract_id])->result();
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    
    // belongsTo
    public function unit($id) {
        $this->load->model('Unit_model');
        return $this->Unit_model->find($id);
    }

    public function get_all_query($contract_id)
    {
        $query = $this->db->
            query("SELECT
                A.contract_id, A.unit_id, B.unit_size,
                B.rate AS unit_rate, B.discount AS unit_discount, B.tax AS unit_tax, B.total AS unit_total,
                C.rate AS charge_rate, C.discount AS charge_discount, C.tax AS charge_tax, C.total AS charge_total
            FROM
            (
                SELECT contract_id, unit_id FROM tnc_c_new_contract_detail GROUP BY contract_id, unit_id
            ) AS A
            LEFT OUTER JOIN
            (
                SELECT * FROM tnc_c_new_contract_detail WHERE contract_id = 6 AND type_item = 'Unit'
            ) AS B ON A.unit_id = B.unit_id AND A.contract_id = B.contract_id
            LEFT OUTER JOIN
            (
                SELECT * FROM tnc_c_new_contract_detail WHERE contract_id = 6 AND type_item = 'Charge'
            ) AS C ON A.unit_id = C.unit_id AND A.contract_id = C.contract_id
            WHERE A.contract_id = $contract_id
        ")->result();

        return $query;
    }
}
