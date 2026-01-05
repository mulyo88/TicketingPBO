<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incoming extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Building_model');
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
		$this->load->model('Tenancy/Inventory/MasterData/Departement_model');
		$this->load->model('Tenancy/Inventory/Stock/Item_model');
		$this->load->model('Tenancy/Inventory/Stock/Incoming_model');
		$this->load->model('Tenancy/Inventory/Stock/Incoming_Detail_model');
        $this->load->model('Tenancy/MasterData/Bussiness_model');

		$this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

	private function create_series($code, $building_id, $date)
    {
        $format = $this->Common_Code_model->get_by_code_last('SERIES_INCOMING');
        if ($format) {
            $counter = $this->Incoming_model->get_series($format->name, $building_id, $date)->total + 1;
            $month = $this->symbol_number(date('m', strtotime($date)));
            $year = date('Y', strtotime($date));
            $series = $this->formatZero($counter) . $format->name .$code . '/' .$month . '/' .$year;
            return [$series, $format->name];
        } else {
            return ['unformated', 'unformated'];
        }
    }

    private function symbol_number($value)
    {
        $symbol_number = [
            1000 => 'M',
            900  => 'CM',
            500  => 'D',
            400  => 'CD',
            100  => 'C',
            90   => 'XC',
            50   => 'L',
            40   => 'XL',
            10   => 'X',
            9    => 'IX',
            5    => 'V',
            4    => 'IV',
            1    => 'I'
        ];

        $result = '';
        foreach ($symbol_number as $val => $symbol) {
            while ($value >= $val) {
                $value -= $val;
                $result .= $symbol;
            }
        }

        return $result;
    }

    private function formatZero($value)
    {
        return str_pad($value, 4, "0", STR_PAD_LEFT);
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
		$data['judul'] = 'Incoming Stocks';
		$data['bodyclass'] = 'skin-blue';

        $first_date = date('Y-m-01');
        $last_date = date('Y-m-t');
        
        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['area']       = $this->input->get('area');
        $data['item_code']  = $this->input->get('item_code');
        $data['item_name']  = $this->input->get('item_name');
        $building_id        = $this->Building_model->find_code($data['area']);

        $data['building']   = $this->Building_model->get_all();
		$data['results']    = $this->Incoming_model->get_all([
            'date_from'     => $data['date_from'],
            'date_to'       => $data['date_to'],
            'building_id'   => $building_id ? $building_id->id : null,
        ]);

        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            $p->building = $this->Incoming_model->building($p->building_id);
            // morph
            $p->from = $this->Incoming_model->get_from($p);
            // hasMany
            $p->detail = $this->Incoming_model->incoming_detail($p->id);
            foreach ($p->detail as $u) {
                // belongsTo
                $u->item = $this->Incoming_Detail_model->item($u->item_id);
            }
        }

        // extra filter ****************************
        $filtered = [];
        $item_code   = $data['item_code'];
        $item_name = $data['item_name'];

        foreach ($data['results'] as $p) {
            foreach ($p->detail as $d) {
                $matchitem_code   = true;
                $matchitem_name = true;

                // seem LIKE '%xxx%'
                if (!empty($item_code)) {
                    $matchitem_code = stripos($d->item->KdBarang, $item_code) !== false;
                }

                if (!empty($item_name)) {
                    $matchitem_name = stripos($d->item->NmBarang, $item_name) !== false;
                }

                if ($matchitem_code && $matchitem_name) {
                    $filtered[] = $p;
                    break;
                }
            }
        }

        $data['results'] = $filtered;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Stock/Incoming/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $data['judul'] = 'Create Incoming Stock';
		$data['bodyclass'] = 'skin-blue';
		$data['input_type'] = 'create';

        $data['building'] = $this->Building_model->get_all();
        $data['from_type'] = $this->Common_Code_model->get_by_code('PARTY');
        $data['result'] = null;
        $data['detail'] = null;

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
        $this->load->view('Tenancy/Inventory/Stock/Incoming/create', $data);
        $this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('total_qty', 'Qty', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('building_id', 'Building', 'required|numeric');
        $this->form_validation->set_rules('from_type', 'Incoming Party', 'required|numeric');
        $this->form_validation->set_rules('from_id', 'Incoming From', 'required|numeric');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
            redirect('Tenancy/Inventory/Stock/Incoming/create');
        }

        $this->db->trans_begin();
        try {
            $series = $this->create_series($this->input->post('building_code'), $this->input->post('building_id'), date('Y-m-d', strtotime($this->input->post('date_trans'))));
            $date = new \Datetime('now');
            $common = $this->Common_Code_model->find($this->input->post('from_type'));

            // Common_Code_model
            $this->Incoming_model->insert([
                'series'  => $series[0],
                'series_code'  => $series[1],
                'date_trans'  => date('Y-m-d', strtotime($this->input->post('date_trans'))),
                'building_id'  => $this->input->post('building_id'),
                'from_type'  => $common->name,
                'from_id'  => $this->input->post('from_id'),
                'qty'  => unformat_number($this->input->post('total_qty')),
                'note'  => $this->input->post('note'),
                'is_active'  => 1,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
            $header_id = $this->db->insert_id();

            foreach($this->input->post('item') as $value){
                $item_id = $value['LedgerNo'];
                $qty = unformat_number($value['qty']);

                $this->Incoming_Detail_model->insert([
                    'incoming_id'  => $header_id,
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'INCOMING',
                    'mode'  => 'INSERT',
                ];
                
                $this->Item_model->update_stock_in($data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Inventory/Stock/Incoming/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Inventory/Stock/Incoming/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Stock/Incoming/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $data['result'] = $this->Incoming_model->get_by_id($id);

        $data['judul'] = $data['result']->series;
        $data['bodyclass'] = 'skin-blue';
		$data['input_type'] = 'edit';

        $data['building'] = $this->Building_model->get_all();
        $data['from_type'] = $this->Common_Code_model->get_by_code('PARTY');
        $data['from_type_common'] = $this->Common_Code_model->get_by_name('PARTY', $data['result']->from_type);

        $data['detail'] = $this->Incoming_model->incoming_detail($data['result']->id);
        foreach ($data['detail'] as $u) {
            // belongsTo
            $u->item = $this->Incoming_Detail_model->item($u->item_id);
        }

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
        $this->load->view('Tenancy/Inventory/Stock/Incoming/edit', $data);
        $this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('total_qty', 'Qty', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('building_id', 'Building', 'required|numeric');
        $this->form_validation->set_rules('from_type', 'Incoming Party', 'required|numeric');
        $this->form_validation->set_rules('from_id', 'Incoming To', 'required|numeric');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            
            is_pesan('error','Unfornuately, something went wrong.');
            redirect('Tenancy/Inventory/Stock/Incoming/edit/'.$id);
        }

        $this->db->trans_begin();
        try {
            $date = new \Datetime('now');
            $common = $this->Common_Code_model->find($this->input->post('from_type'));

            $this->Incoming_model->update($id, [
                'date_trans'  => date('Y-m-d', strtotime($this->input->post('date_trans'))),
                'building_id'  => $this->input->post('building_id'),
                'from_type'  => $common->name,
                'from_id'  => $this->input->post('from_id'),
                'qty'  => unformat_number($this->input->post('total_qty')),
                'note'  => $this->input->post('note'),
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            $detail = $this->Incoming_Detail_model->get_by_incoming_id($id);
            $this->Incoming_Detail_model->delete_by_incoming_id($id);
            foreach($detail as $value) {
                $item_id = $value->item_id;
                $qty = $value->qty;

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'INCOMING',
                    'mode'  => 'DELETE',
                ];
                
                $this->Item_model->update_stock_in($data);
            }

            foreach($this->input->post('item') as $value){
                $item_id = $value['LedgerNo'];
                $qty = unformat_number($value['qty']);

                $this->Incoming_Detail_model->insert([
                    'incoming_id'  => $id,
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'INCOMING',
                    'mode'  => 'INSERT',
                ];
                
                $this->Item_model->update_stock_in($data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Inventory/Stock/Incoming/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Inventory/Stock/Incoming/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Stock/Incoming/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $detail = $this->Incoming_Detail_model->get_by_incoming_id($id);
            $this->Incoming_Detail_model->delete_by_incoming_id($id);
            foreach($detail as $value) {
                $item_id = $value->item_id;
                $qty = $value->qty;

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'INCOMING',
                    'mode'  => 'DELETE',
                ];
                
                $this->Item_model->update_stock_in($data);
            }

            $this->Incoming_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Inventory/Stock/Incoming/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Inventory/Stock/Incoming/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Stock/Incoming/index');
        }
	}
}