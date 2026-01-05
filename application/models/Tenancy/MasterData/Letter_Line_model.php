<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Letter_Line_model extends CI_Model
{
    protected $table = 'tnc_m_letter_line';
    protected $primaryKey = 'party_code, party_id, position';

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

    public function get_all_builder()
    {
        $query = $this->db->
        query("SELECT
                A.id, B.name AS party_type, C.name AS party_name, A.name, A.position, A.is_active, A.username, A.created_at, A.updated_at
            FROM
                tnc_m_letter_line AS A
            LEFT OUTER JOIN
                tnc_mp_common_code AS B ON A.party_type = B.id
            LEFT OUTER JOIN
                    tnc_m_bussiness AS C ON A.party_id = C.id
            WHERE B.name = 'BUSSINESS'
            UNION ALL
            SELECT
                A.id, B.name AS party_type, C.name AS party_name, A.name, A.position, A.is_active, A.username, A.created_at, A.updated_at
            FROM
                tnc_m_letter_line AS A
            LEFT OUTER JOIN
                tnc_mp_common_code AS B ON A.party_type = B.id
            LEFT OUTER JOIN
                    tnc_mp_owner_property AS C ON A.party_id = C.id
            WHERE B.name = 'OWNER_PROPERY'
            UNION ALL
            SELECT
                A.id, B.name AS party_type, C.brand AS party_name, A.name, A.position, A.is_active, A.username, A.created_at, A.updated_at
            FROM
                tnc_m_letter_line AS A
            LEFT OUTER JOIN
                tnc_mp_common_code AS B ON A.party_type = B.id
            LEFT OUTER JOIN
                    tnc_tn_tenant AS C ON A.party_id = C.id
            WHERE B.name = 'TENANT'
        ");

        return $query->result();
    }    
}
