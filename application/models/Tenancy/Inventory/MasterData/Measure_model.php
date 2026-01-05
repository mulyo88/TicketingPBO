<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Measure_model extends CI_Model
{
    protected $table = 'tnc_inven_m_measure';
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

    public function get_by_name($name)
    {
        return $this->db->where('name', $name)
                        ->get($this->table)
                        ->result();
    }

    public function get_by_name_last($name)
    {
        return $this->db->where('name', $name)
                        ->where('is_active', 1)
                        ->order_by('id', 'desc')
                        ->get($this->table)
                        ->row();
    }

    public function get_by_name_count($name)
    {
        return $this->db->where('name', $name)
                        ->get($this->table)
                        ->num_rows();
    }

    public function get_by_name_without_id_count($name, $id)
    {
        return $this->db->where('name', $name)
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
