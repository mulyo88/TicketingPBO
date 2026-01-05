<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_Method_model extends CI_Model
{
    protected $table = 'tnc_inven_m_payment_method';
    protected $primaryKey = 'method_id, payment_gate_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($method_id, $payment_gate_id)
    {
        return $this->db->where('method_id', $method_id)
                        ->where('payment_gate_id', $payment_gate_id)
                        ->get($this->table)
                        ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($method_id, $payment_gate_id, $data)
    {
        return $this->db->where('method_id', $method_id)
                        ->where('payment_gate_id', $payment_gate_id)
                        ->update($this->table, $data);
    }

    public function delete($method_id, $payment_gate_id)
    {
        return $this->db->where('method_id', $method_id)
                        ->where('payment_gate_id', $payment_gate_id)
                        ->delete($this->table);
    }

    public function check_exist($method_id, $payment_gate_id)
    {
        return $this->db->where('method_id', $method_id)
                        ->where('payment_gate_id', $payment_gate_id)
                        ->get($this->table)
                        ->row();
    }

    public function check_exist_without_id($method_id_x, $payment_gate_id_x, $method_id, $payment_gate_id)
    {
        if ($method_id_x == $method_id && $payment_gate_id_x == $payment_gate_id) {
            return null;
        } else  {
            return $this->db->where('method_id', $method_id_x)
            ->where('payment_gate_id', $payment_gate_id_x)
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
