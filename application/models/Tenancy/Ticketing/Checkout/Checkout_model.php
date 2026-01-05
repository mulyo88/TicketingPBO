<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout_model extends CI_Model
{
    protected $table = 'tnc_ticket_trans_checkout';
    protected $primaryKey = 'checkin_id, ticket_id, seq';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete($checkin_id, $ticket_id, $seq, $gate_id)
    {
        return $this->db->where('checkin_id', $checkin_id)
                        ->where('ticket_id', $ticket_id)
                        ->where('seq', $seq)
                        ->where('gate_id', $gate_id)
                        ->delete($this->table);
    }

    public function get_by_checkin_id($checkin_id)
    {
        return $this->db->where('checkin_id', $checkin_id)
                        ->get($this->table)
                        ->result();
    }
}
