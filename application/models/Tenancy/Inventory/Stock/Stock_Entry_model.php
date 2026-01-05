<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_Entry_model extends CI_Model
{
    protected $table = 'tnc_inven_st_entry';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/Inventory/Selling/Item_model');
    }

    public function get_all($data = null)
    {
        if (!$data) {
            return $this->db->get($this->table)->result();
        } else {
            $date_from = date('Y-m-d', strtotime($data['date_from']));
            $date_to = date('Y-m-d', strtotime($data['date_to']));
            $area = $data['area'];
            $status = $data['status'];
            
            $query = $this->db->where("date_trans BETWEEN '$date_from' AND '$date_to'");
            if ($area != 'all') {
                $query = $query->where('building_code', $area);
            }

            if ($status != 'all') {
                $query = $query->where('status', $status);
            }

            $query = $query->get($this->table)->result();
            return $query;
        }
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

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // belongsTo
    public function building($code) {
        $this->load->model('Building_model');
        return $this->Building_model->find_code($code);
    }

    // belongsTo
    public function item($id) {
        $this->load->model('Item_model');
        return $this->Item_model->find($id);
    }
}
