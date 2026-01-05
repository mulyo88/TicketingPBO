<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkin_model extends CI_Model
{
    protected $table = 'tnc_ticket_trans_checkin';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/Ticketing/Master/Ticket_model');
        $this->load->model('Tenancy/Ticketing/Checkout/Checkout_model');
        $this->load->model('Tenancy/Ticketing/Checkin_Scan/Checkin_Scan_model');
        $this->load->model('Tenancy/Ticketing/Checkin/Checkin_Barcode_model');
    }

    public function get_all($data = null)
    {
        if (!$data) {
            return $this->db->get($this->table)->result();
        } else {
            $date_from = date('Y-m-d', strtotime($data['date_from']));
            $date_to = date('Y-m-d', strtotime($data['date_to']));

            $vanue_id = $data['vanue_id'];
            if ($data['vanue_id'] == 0) {
                $vanue_id = null;
            }

            $counter_id = $data['counter_id'];
            if ($data['counter_id'] == '0') {
                $counter_id = null;
            }

            $method = isset($data['method']) ? $data['method'] : 'all';
            $cashier = isset($data['cashier']) ? $data['cashier'] : '';
            
            $query = $this->db->where("date_trans BETWEEN '$date_from' AND '$date_to'");
            if ($vanue_id) {
                $query = $query->where('vanue_id', $vanue_id);
            }

            if ($counter_id) {
                $query = $query->where('counter_id', $counter_id);
            }

            if ($method != 'all') {
                $query = $query->where('methode', $method);
            }

            if ($cashier != '') {
                $query = $query->where('username', $cashier);
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

    public function get_series($code, $vanue_id, $date)
    {
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));

        $query = $this->db->
            query("SELECT COUNT(*) AS total FROM tnc_ticket_trans_checkin WHERE series_code = '$code' AND vanue_id = $vanue_id AND YEAR(date_trans) = $year AND MONTH(date_trans) = $month
        ")->row();

        return $query;
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
    public function building($id) {
        $this->load->model('Building_model');
        return $this->Building_model->find($id);
    }

    public function venue($id) {
        $this->load->model('Venue_model');
        return $this->Venue_model->find($id);
    }

    public function counter($id) {
        $this->load->model('Counter_model');
        return $this->Counter_model->find($id);
    }

    public function bank($bank_id) {
        $this->load->model('Bank_model');
        return $this->Bank_model->find($bank_id);
    }

    // hasMany
    public function checkin_detail($checkin_id) {
        $this->load->model('Checkin_Detail_model');
        return $this->Checkin_Detail_model->get_by_checkin_id($checkin_id);
    }

    public function checkin_scan($checkin_id) {
        $this->load->model('Checkin_Scan_model');
        return $this->Checkin_Scan_model->get_by_checkin_id($checkin_id);
    }

    public function checkin_barcode($checkin_id) {
        $this->load->model('Checkin_Barcode_model');
        return $this->Checkin_Barcode_model->get_by_checkin_id($checkin_id);
    }

    public function checkout($checkin_id) {
        $this->load->model('Checkout_model');
        return $this->Checkout_model->get_by_checkin_id($checkin_id);
    }

    // morp
    public function get_party($data)
    {
        if ($data->party_type == 'BANK') {
            $this->load->model('Bank_model');
            return $this->Bank_model->find($data->party_id);
        } elseif ($data->party_type == 'PAYMENT_GATE') {
            $this->load->model('Common_Code_model');
            return $this->Common_Code_model->find($data->party_id);
        } else {
            return null;
        }
    }
}
