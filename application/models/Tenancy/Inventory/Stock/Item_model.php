<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model
{
    protected $table = 'TNC_Inventory';
    protected $primaryKey = 'LedgerNo';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenancy/Inventory/Stock/Stock_Entry_model');
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('LedgerNo', $id)
                        ->get($this->table)
                        ->row();
    }

    public function get_by_code($code)
    {
        return $this->db->where('KdBarang', $code)
                        ->get($this->table)
                        ->row();
    }

    public function get_by_code_area($code, $area)
    {
        return $this->db->where('KdBarang', $code)
                        ->where('Area', $area)
                        ->get($this->table)
                        ->row();
    }

    public function find($id) {
        return $this->db->get_where($this->table, ['LedgerNo' => $id])->row();
    }

    public function get_by_area($area)
    {
        return $this->db->where('Area', $area)
                        ->get($this->table)
                        ->result();
    }

    public function get_by_code_without_id($code, $id)
    {
        return $this->db->where('KdBarang', $code)
                        ->where('LedgerNo !=', $id)
                        ->get($this->table)
                        ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($KdBarang, $data)
    {
        return $this->db->where('KdBarang', $KdBarang)
                        ->update($this->table, $data);
    }

    public function update_stock_ending($LedgerNo, $qty_ending)
    {
        return $this->db->where('LedgerNo', $LedgerNo)
                        ->update($this->table, [
                            'Jumlah' => (string)$qty_ending,
                        ]);
    }

    public function update_stock_inventory($data) {
        $item = $this->find($data->item_id);

        if (isset($data->mode)) {
            if ($data->mode == 'DELETE') {
                $this->Stock_Entry_model->delete($data->id);

                if ($item) {
                    $KdBarang = $item->KdBarang;
                    $date_trans = $data->date_trans;
                    $query = $this->db->query("EXEC dbo.TNC_Inventory_load '$KdBarang', '', '', ''")->row();
        
                    if ($query) {
                        $this->update($KdBarang, [
                            'Jumlah'  => $query->ending,
                        ]);
                    }
                }

                return;
            }
        }

        // INSERT MODE *****************
        $balance = $data->qty;
        $ending = 0;

        if ($item) {
            $KdBarang = $item->KdBarang;
            $date_trans = $data->date_trans;
            $query = $this->db->query("EXEC dbo.TNC_Inventory_load '$KdBarang', '', '', '$date_trans'")->row();

            if ($query) {
                $ending = $query->ending;
                $balance = $balance - $ending;
            }
        }

        // insert trans
        $this->Stock_Entry_model->insert([
            'date_trans'  => $data->date_trans,
            'building_code'  => $data->building_code,
            'item_id'  => $data->item_id,
            'qty'  => $balance,
            'status'  => $data->status,
            'note'  => 'Adj. ' . $balance . ' for qty replace is ' . $data->qty_replacement . ', before stock is ' . $ending,
            'is_active'  => $data->is_active,
            'username'  => $data->username,
            'created_at'  => $data->created_at,
            'updated_at'  => $data->updated_at,
            'qty_replacement'  => $data->qty_replacement,
        ]);

        $query = $this->db->query("EXEC dbo.TNC_Inventory_load '$KdBarang', '', '', ''")->row();
        if ($query) {
            $this->update($KdBarang, [
                'Jumlah'  => $query->ending,
            ]);
        }
    }

    public function update_stock_in($data)
    {
        // insert trans
        if ($data->status == 'ADJUSTMENT') {
            if ($data->mode == 'INSERT') {
                $this->Stock_Entry_model->insert([
                    'date_trans'  => $data->date_trans,
                    'building_code'  => $data->building_code,
                    'item_id'  => $data->item_id,
                    'qty'  => $data->qty,
                    'status'  => $data->status,
                    'is_active'  => $data->is_active,
                    'username'  => $data->username,
                    'created_at'  => $data->created_at,
                    'updated_at'  => $data->updated_at,
                ]);
            } else if ($data->mode == 'DELETE') {
                $this->Stock_Entry_model->delete($data->id);
            }
        }

        // update stock
        $item = $this->find($data->item_id);
        if ($item) {
            $KdBarang = $item->KdBarang;
            if ($data->mode == 'INSERT') {
                $query = $this->db->set('Jumlah', "CAST(NULLIF(Jumlah, '0') AS DECIMAL(18, 2)) + {$data->qty}", false);
            } else if ($data->mode == 'DELETE') {
                $query = $this->db->set('Jumlah', "CAST(NULLIF(Jumlah, '0') AS DECIMAL(18, 2)) - {$data->qty}", false);
            }
    
            $query->where('KdBarang', $KdBarang)->update($this->table);
        }
    }

    public function update_stock_out($data)
    {
        // insert trans
        if ($data->status == 'ABOLISH' || $data->status == 'RETURN') {
            if ($data->mode == 'INSERT') {
                $this->Stock_Entry_model->insert([
                    'date_trans'  => $data->date_trans,
                    'building_code'  => $data->building_code,
                    'item_id'  => $data->item_id,
                    'qty'  => $data->qty,
                    'status'  => $data->status,
                    'is_active'  => $data->is_active,
                    'username'  => $data->username,
                    'created_at'  => $data->created_at,
                    'updated_at'  => $data->updated_at,
                ]);
            } else if ($data->mode == 'DELETE') {
                $this->Stock_Entry_model->delete($data->id);
            }
        }

        // update stock
        $item = $this->find($data->item_id);
        if ($item) {
            $KdBarang = $item->KdBarang;
            if ($data->mode == 'INSERT') {
                $query = $this->db->set('Jumlah', "CAST(NULLIF(Jumlah, '0') AS DECIMAL(18, 2)) - {$data->qty}", false);
            } else if ($data->mode == 'DELETE') {
                $query = $this->db->set('Jumlah', "CAST(NULLIF(Jumlah, '0') AS DECIMAL(18, 2)) + {$data->qty}", false);
            }
    
            $query->where('KdBarang', $KdBarang)->update($this->table);
        }
    }

    public function delete($KdBarang)
    {
        return $this->db->where('KdBarang', $KdBarang)
                        ->delete($this->table);
    }

    public function get_all_procedure()
    {
        $query = $this->db->query("EXEC dbo.TNC_Inventory_load");
        return $query->result();
    }

    public function get_by_search($search, $search_by, $sort_by, $category)
    {
        $query = '';
        if ($search_by == 'search_code') {
            $query = $this->db->like('KdBarang', $search);
        } else if ($search_by == 'search_name') {
            $query = $this->db->like('NmBarang', $search);
        } else {
            $query = $this->db;
        }

        if ($sort_by == 'name_asc') {
            $query = $query->order_by('NmBarang', 'asc');
        } else if ($sort_by == 'name_desc') {
            $query = $query->order_by('NmBarang', 'desc');
        }

        if ($category != 'ALL') {
            $query = $query->where('Kategori', $category);
        }

        return $query->get($this->table)->result();
    }
}
