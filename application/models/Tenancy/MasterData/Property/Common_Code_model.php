<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_Code_model extends CI_Model
{
    protected $table = 'tnc_mp_common_code';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get($this->table)
                        ->row();
    }

    public function get_by_code($code)
    {
        return $this->db->where('code', $code)
                        ->get($this->table)
                        ->result();
    }

    public function get_by_code_last($code)
    {
        return $this->db->where('code', $code)
                        ->where('is_active', 1)
                        ->order_by('id', 'desc')
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

    public function get_by_is_active($status)
    {
        return $this->db->where('is_active', $status)
                        ->get($this->table)
                        ->result();
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
                A.code, COUNT(B.id) AS tnc_mp_common_code_count, MAX(CONVERT(INT, B.is_Active)) AS is_active, MAX(B.username) AS username, MAX(B.updated_at) AS updated_at
            FROM
            (
                SELECT
                    code
                FROM
                    tnc_mp_common_code
                GROUP BY code
            ) AS A
            LEFT OUTER JOIN
                tnc_mp_common_code AS B ON A.code = B.code
            GROUP BY A.code
        ");

        return $query->result();
    }
}
