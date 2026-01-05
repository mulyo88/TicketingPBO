<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkin_Detail_model extends CI_Model
{
    protected $table = 'tnc_ticket_trans_checkin_detail';
    protected $primaryKey = 'checkin_id, ticket_id';

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

    public function delete_by_checkin_id($checkin_id)
    {
        return $this->db->where('checkin_id', $checkin_id)
                        ->delete($this->table);
    }

    public function get_by_checkin_id($checkin_id) {
        return $this->db->get_where($this->table, ['checkin_id' => $checkin_id])->result();
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    
    // belongsTo
    public function ticket($id) {
        $this->load->model('Ticket_model');
        return $this->Ticket_model->find($id);
    }
}
