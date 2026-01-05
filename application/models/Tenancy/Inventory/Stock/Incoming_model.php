<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incoming_model extends CI_Model
{
    protected $table = 'tnc_inven_st_incoming';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/Inventory/MasterData/Departement_model');
		$this->load->model('Tenancy/Inventory/Selling/Customer_model');
		$this->load->model('Tenancy/Inventory/Buying/Supplier_model');
        $this->load->model('Tenancy/MasterData/Property/Building_model');
    }

    public function get_all($data = null)
    {
        if (!$data) {
            return $this->db->get($this->table)->result();
        } else {
            $date_from = date('Y-m-d', strtotime($data['date_from']));
            $date_to = date('Y-m-d', strtotime($data['date_to']));
            $building_id = $data['building_id'];
            $userlogin = isset($data['userlogin']) ? $data['userlogin'] : '';

            $query = $this->db->where("date_trans BETWEEN '$date_from' AND '$date_to'");
            if ($building_id) {
                $query = $query->where('building_id', $building_id);
            }

            if ($userlogin != '') {
                $query = $query->where('username', $userlogin);
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
            query("SELECT COUNT(*) AS total FROM tnc_inven_st_incoming WHERE series_code = '$code' AND building_id = $building_id AND YEAR(date_trans) = $year AND MONTH(date_trans) = $month
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

    // hasMany
    public function incoming_detail($incoming_id) {
        $this->load->model('Incoming_Detail_model');
        return $this->Incoming_Detail_model->get_by_incoming_id($incoming_id);
    }

    // morp
    public function get_from($data)
    {
        if ($data->from_type == 'DEPARTEMENT') {
            $this->load->model('departement_model');
            return $this->Departement_model->find($data->from_id);
        } elseif ($data->from_type == 'CUSTOMER') {
            $this->load->model('customer_model');
            return $this->Customer_model->find($data->from_id);
        } elseif ($data->from_type == 'SUPPLIER') {
            $this->load->model('supplier_model');
            return $this->Supplier_model->find($data->from_id);
        } elseif ($data->from_type == 'BUILDING') {
            $this->load->model('building_model');
            return $this->Building_model->find($data->from_id);
        } else {
            return null;
        }
    }
}
