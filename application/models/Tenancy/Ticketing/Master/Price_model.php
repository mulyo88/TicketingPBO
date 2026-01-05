<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Price_model extends CI_Model
{
    protected $table = 'tnc_ticket_m_price';
    protected $primaryKey = 'ticket_id, day_name';

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

    public function delete($ticket_id)
    {
        return $this->db->where('ticket_id', $ticket_id)
                        ->delete($this->table);
    }

    public function get_by_id($ticket_id)
    {
        return $this->db->where('ticket_id', $ticket_id)
                        ->get($this->table)
                        ->result();
    }
}
