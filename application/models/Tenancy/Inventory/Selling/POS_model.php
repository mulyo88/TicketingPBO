<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class POS_model extends CI_Model
{
    protected $table = 'tnc_pos_s_pos';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($data = null)
    {
        if (!$data) {
            return $this->db->get($this->table)->result();
        } else {
            $date_from = date('Y-m-d', strtotime($data['date_from']));
            $date_to = date('Y-m-d', strtotime($data['date_to']));
            $building_id = $data['building_id'];
            $method = isset($data['method']) ? $data['method'] : 'all';
            $cashier = isset($data['cashier']) ? $data['cashier'] : '';
            
            $query = $this->db->where("date_trans BETWEEN '$date_from' AND '$date_to'");
            if ($building_id) {
                $query = $query->where('building_id', $building_id);
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

    public function get_series($code, $building_id, $date)
    {
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));

        $query = $this->db->
            query("SELECT COUNT(*) AS total FROM tnc_pos_s_pos WHERE series_code = '$code' AND building_id = $building_id AND YEAR(date_trans) = $year AND MONTH(date_trans) = $month
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
    public function customer($id) {
        $this->load->model('customer_model');
        return $this->Customer_model->find($id);
    }

    public function building($id) {
        $this->load->model('Building_model');
        return $this->Building_model->find($id);
    }

    public function bank($bank_id) {
        $this->load->model('Bank_model');
        return $this->Bank_model->find($bank_id);
    }

    // hasMany
    public function pos_detail($pos_id) {
        $this->load->model('POS_Detail_model');
        return $this->POS_Detail_model->get_by_pos_id($pos_id);
    }

    public function pos_detail_edit($contract_id) {
        $this->load->model('POS_Detail_model');
        return $this->POS_Detail_model->get_all_query($contract_id);
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
