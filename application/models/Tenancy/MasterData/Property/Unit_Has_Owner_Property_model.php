<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_Has_Owner_Property_model extends CI_Model
{

    protected $table = 'tnc_mp_unit_has_owner_property';
    protected $primaryKey = 'unit_id, owner_property_id';

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
        return $this->db->where('unit_id', $id)
                        ->delete($this->table);
    }

    public function get_by_unit_id_builder($unit_id)
    {
        $query = $this->db->
        query("SELECT 
                A.unit_id, A.owner_property_id, B.name
            FROM 
                tnc_mp_unit_has_owner_property AS A
            LEFT OUTER JOIN
                tnc_mp_owner_property AS B ON A.owner_property_id = B.id
            WHERE A.unit_id = $unit_id
        ");

        return $query->result();
    }
}
