<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_model extends CI_Model
{
    protected $table = 'tnc_tn_document';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get($this->table)
                        ->row();
    }

    public function get_by_is_active($status)
    {
        return $this->db->where('is_active', $status)
                        ->get($this->table)
                        ->result();
    }

    public function get_by_id_count($id)
    {
        return $this->db->where('tenant_id', $id)
                        ->get($this->table)
                        ->num_rows();
    }

    public function get_by_tenant_id($tenant_id)
    {
        return $this->db->where('tenant_id', $tenant_id)
                        ->get($this->table)
                        ->result();
    }

    public function get_by_tenant_id_common_id($tenant_id, $common_id)
    {
        return $this->db->where('tenant_id', $tenant_id)
                        ->where('common_id', $common_id)
                        ->get($this->table)
                        ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($tenant_id, $common_id, $data)
    {
        return $this->db->where('tenant_id', $tenant_id)
                        ->where('common_id', $common_id)
                        ->update($this->table, $data);
    }

    public function delete($tenant_id, $common_id)
    {
        return $this->db->where('tenant_id', $tenant_id)
                        ->where('common_id', $common_id)
                        ->delete($this->table);
    }

    public function delete_all($tenant_id)
    {
        return $this->db->where('tenant_id', $tenant_id)
                        ->delete($this->table);
    }

    public function get_all_builder()
    {
        $query = $this->db->
        query("DECLARE @document_total DECIMAL = (SELECT ISNULL(COUNT(*), 0) FROM tnc_mp_common_code WHERE code = 'DOCUMENT_LOO')
            SELECT
                A.id, A.code, A.owner, A.brand, A.product, @document_total AS document_total, COUNT(B.tenant_id) AS document_count, COUNT(B.tenant_id) / @document_total * 100 AS document_progress, A.is_active, A.username, MAX(B.created_at) AS created_at, MAX(B.updated_at) AS updated_at
            FROM
                tnc_tn_tenant AS A
            LEFT OUTER JOIN
                tnc_tn_document AS B ON B.tenant_id = A.id
            GROUP BY
                A.id, A.code, A.owner, A.brand, A.product, A.is_active, A.username
        ");

        return $query->result();
    }

    // belongsTo
    public function common_code($common_id) {
        $this->load->model('Common_Code_model');
        return $this->Common_Code_model->find($common_id);
    }
}
