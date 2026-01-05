<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_model extends CI_Model
{
    protected $table = 'tnc_mp_unit';
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

    public function get_by_building_id($building_id)
    {
        $query = $this->db->
        query("SELECT
                E.name AS building_name, A.*, D.contract_id
            FROM
                tnc_mp_unit AS A
            LEFT OUTER JOIN
            (
                SELECT B.unit_id, B.contract_id FROM
                (
                    SELECT unit_id, MAX(contract_id) AS contract_id FROM tnc_c_new_contract_detail WHERE type_item = 'Unit' GROUP BY unit_id
                ) AS B
                INNER JOIN tnc_c_new_contract AS C ON B.contract_id = C.id AND CONVERT(DATE, C.tenant_end_date_operation) >= CONVERT(DATE, GETDATE())

            ) AS D ON A.id = D.unit_id
            LEFT OUTER JOIN
                tnc_mp_building AS E ON A.building_id = E.id
            WHERE E.id = $building_id AND D.contract_id IS NULL;
        ");

        return $query->result();
    }

    public function get_by_code($code)
    {
        return $this->db->where('code', "'$code'")
                        ->get($this->table)
                        ->row();
    }

    public function get_by_code_count($code)
    {
        return $this->db->where('code', "'$code'")
                        ->get($this->table)
                        ->num_rows();
    }

    public function get_by_code_without_id_count($code, $id)
    {
        return $this->db->where('code', "'$code'")
                        ->where('id !=', $id)
                        ->get($this->table)
                        ->num_rows();
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

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_all_builder()
    {
        $query = $this->db->
        query("SELECT
                E.name AS building_name, A.*, D.contract_id
            FROM
                tnc_mp_unit AS A
            LEFT OUTER JOIN
            (
                SELECT B.unit_id, B.contract_id FROM
                (
                    SELECT unit_id, MAX(contract_id) AS contract_id FROM tnc_c_new_contract_detail WHERE type_item = 'Unit' GROUP BY unit_id
                ) AS B
                INNER JOIN tnc_c_new_contract AS C ON B.contract_id = C.id AND CONVERT(DATE, C.tenant_end_date_operation) >= CONVERT(DATE, GETDATE())

            ) AS D ON A.id = D.unit_id
            LEFT OUTER JOIN
                tnc_mp_building AS E ON A.building_id = E.id
        ");

        return $query->result();
    }
}
