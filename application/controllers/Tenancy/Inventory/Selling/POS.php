<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class POS extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Building_model');
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
		$this->load->model('Tenancy/Inventory/Selling/Customer_model');
		$this->load->model('Tenancy/MasterData/Tax_model');
        $this->load->model('Tenancy/MasterData/Bank_model');
		$this->load->model('Tenancy/Inventory/Stock/Item_model');
		$this->load->model('Tenancy/Inventory/Selling/POS_model');
		$this->load->model('Tenancy/Inventory/Selling/POS_Detail_model');
        $this->load->model('Tenancy/MasterData/Bussiness_model');

		$this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

	private function create_series($code, $building_id, $date)
    {
        $format = $this->Common_Code_model->get_by_code_last('SERIES_POS');
        if ($format) {
            $counter = $this->POS_model->get_series($format->name, $building_id, $date)->total + 1;
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
		$data['judul'] = 'Point of Sales';
		$data['bodyclass'] = 'skin-blue';
		
        $first_date = date('Y-m-01');
        $last_date = date('Y-m-t');
        
        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['area']       = $this->input->get('area');
        $data['item_code']  = $this->input->get('item_code');
        $data['item_name']  = $this->input->get('item_name');
        $building_id        = $this->Building_model->find_code($data['area']);

        $data['building'] = $this->Building_model->get_all();
		$data['results'] = $this->POS_model->get_all([
            'date_from'     => $data['date_from'],
            'date_to'       => $data['date_to'],
            'building_id'   => $building_id ? $building_id->id : null,
        ]);
        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            $p->building = $this->POS_model->building($p->building_id);
            $p->customer = $this->POS_model->customer($p->customer_id);
            // morph
            $p->party = $this->POS_model->get_party($p);
            // hasMany
            $p->detail = $this->POS_model->pos_detail($p->id);
            foreach ($p->detail as $u) {
                // belongsTo
                $u->item = $this->POS_Detail_model->item($u->item_id);
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
		$this->load->view('Tenancy/Inventory/Selling/POS/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create($building_code)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['title'] = 'Point of Sales';

		$data['input_type'] = 'create';
        $data['building'] = $this->Building_model->get_by_code($building_code);
        $data['customer'] = $this->Customer_model->get_by_default();
        $data['tax'] = $this->Tax_model->get_last();
        $data['method'] = $this->Common_Code_model->get_by_code('PAYMENT_METHODE');
        $data['category'] = $this->Common_Code_model->get_by_code('Category');
        $data['building_code'] = $building_code;
        $data['result'] = null;
        $data['detail'] = null;

        $this->load->view('Tenancy/Inventory/Selling/POS/create', $data);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store($building_code)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('qty', 'Unit Qty', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('discount', 'Discount', 'required|numeric');
        $this->form_validation->set_rules('tax', 'Tax', 'required|numeric');
        $this->form_validation->set_rules('payment', 'Payment', 'required|numeric');
        $this->form_validation->set_rules('method', 'Method', 'required');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => 'data need value']);

            redirect('Tenancy/Inventory/Selling/POS/create/'.$building_code);
        }

        $this->db->trans_begin();
        try {
            $series = $this->create_series($building_code, $this->input->post('building_id'), date('Y-m-d H:i:s'));
            $buss = $this->Bussiness_model->get_last();
            $date = new \Datetime('now');

            $this->POS_model->insert([
                'series'  => $series[0],
                'series_code'  => $series[1],
                'date_trans'  => date('Y-m-d'),
                'building_id'  => $this->input->post('building_id'),
                'bussiness_id'  => $buss->id,
                'customer_id'  => $this->input->post('customer_id'),
                'qty'  => unformat_number($this->input->post('qty')),
                'total'  => unformat_number($this->input->post('total')),
                'discount'  => unformat_number($this->input->post('discount')),
                'tax'  => unformat_number($this->input->post('tax')),
                'payment'  => unformat_number($this->input->post('payment')),
                'methode'  => $this->input->post('method'),
                'party_id'  => $this->input->post('party_id'),
                'party_type'  => $this->input->post('party_type') == '' ? null : $this->input->post('party_type'),
                'order_id'  => $this->input->post('order_id') == '' ? null : $this->input->post('order_id'),
                'is_active'  => 1,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
            $header_id = $this->db->insert_id();

            foreach($this->input->post('cart') as $value){
                $item_id = $value['LedgerNo'];
                $qty = unformat_number($value['qty']);
                $rate = unformat_number($value['harga_jual']);

                $this->POS_Detail_model->insert([
                    'pos_id'  => $header_id,
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'rate'  => $rate,
                    'discount'  => 0,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'POS',
                    'mode'  => 'INSERT',
                ];
                
                $this->Item_model->update_stock_out($data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => 'Insert failed, please try again']);
                redirect('Tenancy/Inventory/Selling/POS/create/'.$building_code);
            } else {
                $this->db->trans_commit();
                
                $this->session->set_flashdata('flash', ['type' => 'success', 'message' => 'Inserted successfully!']);
                $this->session->set_flashdata('print', ['id' => $header_id, 'is_print' => $this->input->post('is_print')]);

                redirect('Tenancy/Inventory/Selling/POS/create/'.$building_code);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => $e->getMessage()]);
            redirect('Tenancy/Inventory/Selling/POS/create/'.$building_code);
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $data['result'] = $this->POS_model->get_by_id($id);
        $data['building'] = $this->Building_model->get_by_id($data['result']->building_id);

		$data['title'] = $data['result']->series;
		$data['input_type'] = 'edit';

        $data['customer'] = $this->Customer_model->get_by_id($data['result']->customer_id);
        $data['tax'] = $this->Tax_model->get_last();
        $data['method'] = $this->Common_Code_model->get_by_code('PAYMENT_METHODE');
        $data['category'] = $this->Common_Code_model->get_by_code('Category');
        $data['building_code'] = $data['building']->code;

        $data['detail'] = $this->POS_model->pos_detail($data['result']->id);
        foreach ($data['detail'] as $u) {
            // belongsTo
            $u->item = $this->POS_Detail_model->item($u->item_id);
        }

        $this->load->view('Tenancy/Inventory/Selling/POS/edit', $data);
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('qty', 'Unit Qty', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('discount', 'Discount', 'required|numeric');
        $this->form_validation->set_rules('tax', 'Tax', 'required|numeric');
        $this->form_validation->set_rules('payment', 'Payment', 'required|numeric');
        $this->form_validation->set_rules('method', 'Method', 'required');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => 'data need value']);

            redirect('Tenancy/Inventory/Selling/POS/edit/'.$id);
        }

        $this->db->trans_begin();
        try {
            $pos = $this->POS_model->get_by_id($id);
            $building = $this->Building_model->get_by_id($pos->building_id);
            $date = new \Datetime('now');

            $this->POS_model->update($id, [
                'qty'  => unformat_number($this->input->post('qty')),
                'total'  => unformat_number($this->input->post('total')),
                'discount'  => unformat_number($this->input->post('discount')),
                'tax'  => unformat_number($this->input->post('tax')),
                'payment'  => unformat_number($this->input->post('payment')),
                'methode'  => $this->input->post('method'),
                'party_id'  => $this->input->post('party_id'),
                'party_type'  => $this->input->post('party_type') == '' ? null : $this->input->post('party_type'),
                'order_id'  => $this->input->post('order_id') == '' ? null : $this->input->post('order_id'),
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            $detail = $this->POS_Detail_model->get_by_pos_id($id);
            $this->POS_Detail_model->delete_by_pos_id($id);
            foreach($detail as $value) {
                $item_id = $value->item_id;
                $qty = $value->qty;

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'POS',
                    'mode'  => 'DELETE',
                ];
                
                $this->Item_model->update_stock_out($data);
            }

            foreach($this->input->post('cart') as $value){
                $item_id = $value['LedgerNo'];
                $qty = unformat_number($value['qty']);
                $rate = unformat_number($value['harga_jual']);

                $this->POS_Detail_model->insert([
                    'pos_id'  => $id,
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'rate'  => $rate,
                    'discount'  => 0,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'POS',
                    'mode'  => 'INSERT',
                ];
                
                $this->Item_model->update_stock_out($data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => 'Update failed, please try again']);
                redirect('Tenancy/Inventory/Selling/POS/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                $this->session->set_flashdata('flash', ['type' => 'warning', 'message' => 'Update successfully!']);
                $this->session->set_flashdata('print', ['id' => $id, 'is_print' => $this->input->post('is_print')]);

                redirect('Tenancy/Inventory/Selling/POS/create/'.$building->code);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => $e->getMessage()]);
            redirect('Tenancy/Inventory/Selling/POS/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $detail = $this->POS_Detail_model->get_by_pos_id($id);
            $this->POS_Detail_model->delete_by_pos_id($id);
            foreach($detail as $value) {
                $item_id = $value->item_id;
                $qty = $value->qty;

                $data = (object) [
                    'item_id'  => $item_id,
                    'qty'  => $qty,
                    'status'  => 'POS',
                    'mode'  => 'DELETE',
                ];
                
                $this->Item_model->update_stock_out($data);
            }

            $this->POS_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Inventory/Selling/POS/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Inventory/Selling/POS/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Selling/POS/index');
        }
	}

    public function payment_gate() {
        $data['data'] = $this->input->get('data');
        $data['party_id'] = $this->input->get('party_id');
        $this->load->view('Tenancy/Inventory/Selling/POS/payment_gate', $data);
    }

    public function print_struk($id) {
        $data['judul'] = 'POS Billing Receipt';

        $data['pos'] = $this->POS_model->get_by_id($id);
        $data['building'] = $this->Building_model->get_by_id($data['pos']->building_id);
        $data['detail'] = $this->POS_model->pos_detail($id);
        foreach ($data['detail'] as $u) {
            // belongsTo
            $u->item = $this->POS_Detail_model->item($u->item_id);
        }

        $data['id'] = $id;
        $this->load->view('Tenancy/Inventory/Selling/POS/partials/struk', $data);
    }
}