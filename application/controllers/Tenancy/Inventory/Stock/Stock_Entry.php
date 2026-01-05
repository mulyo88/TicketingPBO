<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_Entry extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Building_model');
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
		$this->load->model('Tenancy/Inventory/Stock/Item_model');
		$this->load->model('Tenancy/Inventory/Stock/Stock_Entry_model');

		$this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    public function not_zero($val)
    {
        if ($val == 0) {
            $this->form_validation->set_message('not_zero', '{field} need value.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'Stock Entries';
		$data['bodyclass'] = 'skin-blue';
		
        $first_date = date('Y-m-01');
        $last_date = date('Y-m-t');
        
        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['area']       = $this->input->get('area') == '' ? 'all' : $this->input->get('area');
        $data['status']     = $this->input->get('status') == '' ? 'all' : $this->input->get('status');

        $data['building'] = $this->Building_model->get_all();
        $data['status_x'] = $this->Common_Code_model->get_by_code('STATUS_STOCK_ENTRY');
		$data['results'] = $this->Stock_Entry_model->get_all([
            'date_from'     => $data['date_from'],
            'date_to'       => $data['date_to'],
            'area'          => $data['area'],
            'status'        => $data['status'],
        ]);

        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            $p->building = $this->Stock_Entry_model->building($p->building_code);
            $p->item = $this->Stock_Entry_model->item($p->item_id);
        }

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Stock/Stock_Entry/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $data['judul'] = 'Create Stock Entry';
		$data['bodyclass'] = 'skin-blue';

        $data['building'] = $this->Building_model->get_all();
        $data['status'] = $this->Common_Code_model->get_by_code('STATUS_STOCK_ENTRY');

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
        $this->load->view('Tenancy/Inventory/Stock/Stock_Entry/create', $data);
        $this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $this->form_validation->set_rules('building_code', 'Building', 'required');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
            redirect('Tenancy/Inventory/Stock/Stock_Entry/create');
        }

        $this->db->trans_begin();
        try {
            $date = new \Datetime('now');
            $date_trans = date('Y-m-d', strtotime($this->input->post('date_trans')));

            foreach($this->input->post('item') as $value){
                $item_id = $value['LedgerNo'];
                $building_code = $this->input->post('building_code');
                $qty = unformat_number($value['qty']);
                $status = $value['status'];
                $note = $value['note'];

                $data = (object) [
                    'date_trans'  => $date_trans,
                    'building_code'  => $building_code,
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => $status,
                    'note'  => $note,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                    'qty_replacement'  => $qty,
                    'mode'  => 'INSERT',
                ];
                
                if ($status == 'INVENTORY') {
                    $this->Item_model->update_stock_inventory($data);
                } else if ($status == 'ADJUSTMENT') {
                    $this->Item_model->update_stock_in($data);
                } else if ($status == 'ABOLISH') {
                    $this->Item_model->update_stock_out($data);
                } else if ($status == 'RETURN') {
                    $this->Item_model->update_stock_out($data);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Inventory/Stock/Stock_Entry/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Inventory/Stock/Stock_Entry/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Stock/Stock_Entry/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $date = new \Datetime('now');

        $this->db->trans_begin();
        try {
            $stock = $this->Stock_Entry_model->find($id);
            $item = $this->Item_model->find($stock->item_id);

            $data = (object) [
                'id'  => $id,
                'date_trans'  => date('Y-m-d', strtotime($stock->date_trans)),
                'building_code'  => $item->Area,
                'item_id'  => $stock->item_id,
                'qty'  => $stock->qty,
                'status'  => $stock->status,
                'note'  => $stock->note,
                'is_active'  => 1,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
                'qty_replacement'  => $stock->qty,
                'mode'  => 'DELETE',
            ];
            
            if ($stock->status == 'INVENTORY') {
                $this->Item_model->update_stock_inventory($data);
            } else if ($stock->status == 'ADJUSTMENT') {
                $this->Item_model->update_stock_in($data);
            } else if ($stock->status == 'ABOLISH') {
                $this->Item_model->update_stock_out($data);
            } else if ($stock->status == 'RETURN') {
                $this->Item_model->update_stock_out($data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Inventory/Stock/Stock_Entry/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Inventory/Stock/Stock_Entry/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Stock/Stock_Entry/index');
        }
	}
}