<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmInventory extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models, libraries, helpers here
        // $this->load->model('Inventory_model');

        $this->load->model('Tenancy/Inventory/Stock/Item_model');
        $this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
        $this->load->model('Tenancy/Inventory/MasterData/Departement_model');
        $this->load->model('Tenancy/Inventory/MasterData/Measure_model');
        $this->load->model('Tenancy/Inventory/Stock/Item_Has_Measure_model');
        $this->load->model('Tenancy/Inventory/Buying/Supplier_model');
        $this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/Inventory/Stock/Stock_Entry_model');
    }

    public function index() {
        // List all inventory items
        $data['bodyclass'] = 'sidebar-collapse skin-blue';
        $data['judul']  = 'Form Items';

        // $data['items'] = $this->db->get('TNC_Inventory')->result();
        $data['items'] = $this->Item_model->get_all_procedure();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('Tenancy/Inventory/Stock/Item/EntryInventory', $data);
        $this->load->view('templates/footer');
    
    }

    public function add() {
        $data['bodyclass'] = 'sidebar-collapse skin-blue';
        $data['judul']  = 'Tambah Item';
        

        $data['category'] = $this->Common_Code_model->get_by_code('Category');
        $data['building'] = $this->Building_model->get_by_is_active(1);
        $data['departement'] = $this->Departement_model->get_by_is_active(1);
        $data['supplier'] = $this->Supplier_model->get_by_is_active(1);
        $data['status'] = $this->Common_Code_model->get_by_code('Status');
        $data['uom'] = $this->Measure_model->get_by_is_active(1);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('Tenancy/Inventory/Stock/Item/FrmAddInventory', $data);
        $this->load->view('templates/footer');
    }

    public function AksiTambah()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        // Ambil data dari form
        $branch   = $this->input->post('Area');
        $dept     = $this->input->post('Departement');
        $category = $this->input->post('Kategori');

        // ======== Generate Auto Code =========
        $lastCode = $this->db->select('KdBarang')
            ->from('TNC_Inventory')
            ->like('KdBarang', "$branch-$dept-$category", 'after')
            ->order_by('KdBarang', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($lastCode) {
            $parts = explode('-', $lastCode->KdBarang);
            $lastNumber = intval(end($parts));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        $newCode = "$branch-$dept-$category-$nextNumber";
        // =====================================

        // Ambil semua data form
        $postData = $this->input->post();
        $postData['KdBarang'] = $newCode; // tambahkan KdBarang yang baru ke data yang akan disimpan

        $insert = $this->Item_model->insert([
            'KdBarang'  => $newCode,
            'NmBarang'  => $this->input->post('NmBarang'),
            'Spesifikasi'  => $this->input->post('Spesifikasi'),
            'Area'  => $this->input->post('Area'),
            'Departement'  => $this->input->post('Departement'),
            'Kategori'  => $this->input->post('Kategori'),
            'Satuan'  => $this->input->post('uom_small'),
            'Jumlah'  => unformat_number($this->input->post('Jumlah')),
            'MinStock'  => unformat_number($this->input->post('MinStock')),
            'MaxStock'  => unformat_number($this->input->post('MaxStock')),
            'TglBeli'  => $this->input->post('TglBeli'),
            'TglKadaluarsa'  => $this->input->post('TglKadaluarsa'),
            'Status'  => $this->input->post('Status'),
            'Harga'  => unformat_number($this->input->post('Harga')),
            'harga_jual'  => unformat_number($this->input->post('harga_jual')),
            'Vendor'  => $this->input->post('Vendor'),
            'Catatan'  => $this->input->post('Catatan'),
        ]);
        $item_id = $this->db->insert_id();

        $date = new \Datetime('now');
        // multi uom small *****************
        if ($this->input->post('uom_small') != '') {
            $size = $this->input->post('size_small') != '' ? $this->input->post('size_small') : 0;
            $this->Item_Has_Measure_model->insert([
                'item_id'  => $item_id,
                'name'  => $this->input->post('uom_small'),
                'size'  => $size,
                'is_active'  => 1,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
        }

        // multi uom middle *****************
        if ($this->input->post('uom_middle') != '') {
            $size = $this->input->post('size_middle') != '' ? $this->input->post('size_middle') : 0;
            $this->Item_Has_Measure_model->insert([
                'item_id'  => $item_id,
                'name'  => $this->input->post('uom_middle'),
                'size'  => $size,
                'is_active'  => 1,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
        }

        // multi uom big *****************
        if ($this->input->post('uom_big') != '') {
            $size = $this->input->post('size_big') != '' ? $this->input->post('size_big') : 0;
            $this->Item_Has_Measure_model->insert([
                'item_id'  => $item_id,
                'name'  => $this->input->post('uom_big'),
                'size'  => $size,
                'is_active'  => 1,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
        }

        //add to inventory
        $data = (object) [
            'date_trans'  => $date->format('Y-m-d'),
            'building_code'  => $this->input->post('Area'),
            'item_id'  => $item_id,
            'qty'  => unformat_number($this->input->post('Jumlah')),
            'status'  => 'INVENTORY',
            'is_active'  => 1,
            'username'  => $user_id,
            'created_at'  => $date->format('Y-m-d H:i:s'),
            'updated_at'  => $date->format('Y-m-d H:i:s'),
            'qty_replacement'  => unformat_number($this->input->post('Jumlah')),
        ];
        
        $this->Item_model->update_stock_inventory($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Data inventory berhasil disimpan dengan KdBarang: '.$newCode);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data inventory!');
        }

        redirect('Tenancy/Inventory/Stock/FrmInventory/index');
    }

    public function edit($KdBarang) {
        $data['bodyclass'] = 'sidebar-collapse skin-blue';
        $data['judul']  = 'Edit Item';

        $data['category'] = $this->Common_Code_model->get_by_code('Category');
        $data['building'] = $this->Building_model->get_by_is_active(1);
        $data['departement'] = $this->Departement_model->get_by_is_active(1);
        $data['supplier'] = $this->Supplier_model->get_by_is_active(1);
        $data['status'] = $this->Common_Code_model->get_by_code('Status');
        $data['uom'] = $this->Measure_model->get_by_is_active(1);

        $data['item'] = $this->db->get_where('TNC_Inventory', ['KdBarang' => $KdBarang])->row();
        $data['uom_has_item'] = $this->Item_Has_Measure_model->get_by_item_id($data['item']->LedgerNo);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('Tenancy/Inventory/Stock/Item/FrmEditInventory', $data);
        $this->load->view('templates/footer');
    }

    public function AksiEdit($KdBarang)
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $push_inventory = false;
        $item = $this->Item_model->get_by_code($KdBarang);
        if ($item->Jumlah != unformat_number($this->input->post('Jumlah'))) {
            $push_inventory = true;
        }

        $postData = $this->input->post();
        $update = $this->Item_model->update($KdBarang, [
            'NmBarang'  => $this->input->post('NmBarang'),
            'Spesifikasi'  => $this->input->post('Spesifikasi'),
            'Area'  => $this->input->post('Area'),
            'Departement'  => $this->input->post('Departement'),
            'Kategori'  => $this->input->post('Kategori'),
            'Satuan'  => $this->input->post('uom_small'),
            'Jumlah'  => unformat_number($this->input->post('Jumlah')),
            'MinStock'  => unformat_number($this->input->post('MinStock')),
            'MaxStock'  => unformat_number($this->input->post('MaxStock')),
            'TglBeli'  => $this->input->post('TglBeli'),
            'TglKadaluarsa'  => $this->input->post('TglKadaluarsa'),
            'Status'  => $this->input->post('Status'),
            'Harga'  => unformat_number($this->input->post('Harga')),
            'harga_jual'  => unformat_number($this->input->post('harga_jual')),
            'Vendor'  => $this->input->post('Vendor'),
            'Catatan'  => $this->input->post('Catatan'),
        ]);

        $this->Item_Has_Measure_model->delete($item->LedgerNo);
        $date = new \Datetime('now');

        // multi uom small *****************
        if ($this->input->post('uom_small') != '') {
            $size = $this->input->post('size_small') != '' ? $this->input->post('size_small') : 0;
            $this->Item_Has_Measure_model->insert([
                'item_id'  => $item->LedgerNo,
                'name'  => $this->input->post('uom_small'),
                'size'  => $size,
                'is_active'  => 1,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
        }

        // multi uom middle *****************
        if ($this->input->post('uom_middle') != '') {
            $size = $this->input->post('size_middle') != '' ? $this->input->post('size_middle') : 0;
            $this->Item_Has_Measure_model->insert([
                'item_id'  => $item->LedgerNo,
                'name'  => $this->input->post('uom_middle'),
                'size'  => $size,
                'is_active'  => 1,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
        }

        // multi uom big *****************
        if ($this->input->post('uom_big') != '') {
            $size = $this->input->post('size_big') != '' ? $this->input->post('size_big') : 0;
            $this->Item_Has_Measure_model->insert([
                'item_id'  => $item->LedgerNo,
                'name'  => $this->input->post('uom_big'),
                'size'  => $size,
                'is_active'  => 1,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
        }

        //add to inventory
        if ($push_inventory == true) {
            $data = (object) [
                'date_trans'  => $date->format('Y-m-d'),
                'building_code'  => $this->input->post('Area'),
                'item_id'  => $item->LedgerNo,
                'qty'  => unformat_number($this->input->post('Jumlah')),
                'status'  => 'INVENTORY',
                'is_active'  => 1,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
                'qty_replacement'  => unformat_number($this->input->post('Jumlah')),
            ];
            
            $this->Item_model->update_stock_inventory($data);
        }

        if ($update) {
            $this->session->set_flashdata('success', 'Data inventory berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data inventory!');
        }

        redirect('Tenancy/Inventory/Stock/FrmInventory/index');
    }


    public function delete($KdBarang) {
        $Item_model = $this->Item_model->get_by_code($KdBarang);
        $this->Item_Has_Measure_model->delete($Item_model->LedgerNo);
        $delete = $this->Item_model->delete($KdBarang);
        
        // Delete an inventory item
        if ($delete) {
            $this->session->set_flashdata('success', 'Data inventory berhasil didelete!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data inventory!');
        }

        redirect('Tenancy/Inventory/Stock/FrmInventory/index');
    }
}