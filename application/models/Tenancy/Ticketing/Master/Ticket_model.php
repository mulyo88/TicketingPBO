<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model
{
    protected $table = 'tnc_ticket_m_ticket';
    protected $primaryKey = 'id, vanue_id, category';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/Ticketing/Master/Price_model');
        $this->load->model('Tenancy/Ticketing/Master/Venue_model');
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_all_is_active($status)
    {
        return $this->db->where('is_active', $status)->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get($this->table)
                        ->row();
    }

    public function get_by_id_category($id, $category)
    {
        return $this->db->where('id', $id)
                        ->where('category', $category)
                        ->get($this->table)
                        ->row();
    }

    public function get_by_vanue($vanue_id, $status = 1)
    {
        if ($status) {
            return $this->db->where('vanue_id', $vanue_id)
                            ->where('is_active', $status)
                            ->get($this->table)
                            ->result();
        } else {
            return $this->db->where('vanue_id', $vanue_id)
                            ->get($this->table)
                            ->result();
        }
    }

    public function check_exists($param, $id = null)
    {
        if ($id) {
            return $this->db->where('vanue_id', $param['vanue_id'])
                            ->where('category', $param['category'])
                            ->where('name', $param['name'])
                            ->where('id !=', $id)
                            ->get($this->table)
                            ->row();
        } else {
            return $this->db->where('vanue_id', $param['vanue_id'])
                            ->where('category', $param['category'])
                            ->where('name', $param['name'])
                            ->get($this->table)
                            ->row();
        }
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
    public function venue($id) {
        $this->load->model('Venue_model');
        return $this->Venue_model->find($id);
    }

    // hasmany
    public function price($ticket_id) {
        $this->load->model('Price_model');
        return $this->Price_model->get_by_id($ticket_id);
    }
}
