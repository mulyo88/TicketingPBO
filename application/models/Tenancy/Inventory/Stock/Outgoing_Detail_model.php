<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outgoing_Detail_model extends CI_Model
{
    protected $table = 'tnc_inven_st_outgoing_detail';
    protected $primaryKey = 'outgoing_id, item_id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/Inventory/Selling/Item_model');
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

    public function delete_by_outgoing_id($outgoing_id)
    {
        return $this->db->where('outgoing_id', $outgoing_id)
                        ->delete($this->table);
    }

    public function get_by_outgoing_id($outgoing_id) {
        return $this->db->get_where($this->table, ['outgoing_id' => $outgoing_id])->result();
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    
    // belongsTo
    public function item($id) {
        $this->load->model('Item_model');
        return $this->Item_model->find($id);
    }
}
