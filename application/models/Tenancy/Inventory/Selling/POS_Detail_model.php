<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class POS_Detail_model extends CI_Model
{
    protected $table = 'tnc_pos_s_pos_detail';
    protected $primaryKey = 'pos_id, item_id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/Inventory/Stock/Item_model');
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

    public function delete_by_pos_id($pos_id)
    {
        return $this->db->where('pos_id', $pos_id)
                        ->delete($this->table);
    }

    public function get_by_pos_id($pos_id) {
        return $this->db->get_where($this->table, ['pos_id' => $pos_id])->result();
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    
    // belongsTo
    public function item($id) {
        return $this->Item_model->find($id);
    }
}
