<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_Has_Measure_model extends CI_Model
{
    protected $table = 'tnc_inven_st_item_has_measure';
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

    public function get_by_item_id($item_id)
    {
        return $this->db->where('item_id', $item_id)
                        ->order_by('id', 'asc')
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

    public function delete($item_id)
    {
        return $this->db->where('item_id', $item_id)
                        ->delete($this->table);
    }
}
