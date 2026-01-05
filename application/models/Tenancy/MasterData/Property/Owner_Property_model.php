<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Owner_Property_model extends CI_Model
{
    protected $table = 'tnc_mp_owner_property';
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
}
