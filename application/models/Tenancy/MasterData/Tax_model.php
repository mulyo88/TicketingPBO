<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax_model extends CI_Model
{
    protected $table = 'tnc_m_tax';
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

    public function get_last()
    {
        return $this->db->where('is_active', 1)
                        ->order_by('date_register', 'desc')
                        ->limit(1)
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
}
