<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_Gate_Key_model extends CI_Model
{
    protected $table = 'tnc_inven_m_payment_gate_has_key';
    protected $primaryKey = 'payment_gate_id, key_id, key';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function find($payment_gate_id) {
        return $this->db->where('payment_gate_id', $payment_gate_id)
                        ->get($this->table)
                        ->result();
    }

    public function get_by_id($payment_gate_id, $key_id)
    {
        return $this->db->where('payment_gate_id', $payment_gate_id)
                        ->where('key_id', $key_id)
                        ->get($this->table)
                        ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($payment_gate_id, $key_id, $data)
    {
        return $this->db->where('payment_gate_id', $payment_gate_id)
                        ->where('key_id', $key_id)
                        ->update($this->table, $data);
    }

    public function delete($payment_gate_id, $key_id)
    {
        return $this->db->where('payment_gate_id', $payment_gate_id)
                        ->where('key_id', $key_id)
                        ->delete($this->table);
    }

    public function check_exist($payment_gate_id, $key_id, $key)
    {
        return $this->db->where('payment_gate_id', $payment_gate_id)
                        ->where('key_id', $key_id)
                        ->where('key', $key)
                        ->get($this->table)
                        ->row();
    }

    public function check_exist_without_id($payment_gate_id_x, $key_id_x, $payment_gate_id, $key_id, $key)
    {
        if ($payment_gate_id_x == $payment_gate_id && $key_id_x == $key_id) {
            return $this->db->where('payment_gate_id', $payment_gate_id)
            ->where('key_id', $key_id)
            ->where('key', $key)
            ->get($this->table)
            ->row();
        } else  {
            return $this->db->where('payment_gate_id', $payment_gate_id_x)
            ->where('key_id', $key_id_x)
            ->where('key', $key)
            ->get($this->table)
            ->row();
        }
    }

    // belongsTo
    public function payment_gate_name($id) {
        $this->load->model('common_code_model');
        return $this->Common_Code_model->find($id);
    }

    // belongsTo
    public function payment_gate_type($id) {
        $this->load->model('common_code_model');
        return $this->Common_Code_model->find($id);
    }
}
