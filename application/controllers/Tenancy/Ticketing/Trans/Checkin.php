<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Building_model');
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
        $this->load->model('Tenancy/Ticketing/Master/Venue_model');
        $this->load->model('Tenancy/Ticketing/Master/Ticket_model');
        $this->load->model('Tenancy/Ticketing/Master/Counter_model');
		$this->load->model('Tenancy/MasterData/Tax_model');
        $this->load->model('Tenancy/MasterData/Bank_model');
		$this->load->model('Tenancy/Ticketing/Checkin/Checkin_model');
		$this->load->model('Tenancy/Ticketing/Checkin/Checkin_Detail_model');
		$this->load->model('Tenancy/Ticketing/Checkin/Checkin_Barcode_model');

		$this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

	private function create_series($code, $vanue_id, $date)
    {
        $query = $this->db->where('code', 'SERIES_CHECKIN')
        ->where('is_active', 1)
        ->order_by('id', 'desc')
        ->get('tnc_ticket_m_common_code')
        ->row();

        $format = $query;
        if ($format) {
            $counter = $this->Checkin_model->get_series($format->name, $vanue_id, $date)->total + 1;
            $month = $this->symbol_number(date('m', strtotime($date)));
            $year = date('Y', strtotime($date));
            $series = $this->formatZero($counter) . $format->name . $code . '/' .$month . '/' .$year;
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
		$data['judul'] = 'Cashier Ticketing';
		$data['bodyclass'] = 'skin-blue';
		
        $first_date = date('Y-m-01');
        $last_date = date('Y-m-t');

        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['vanue_code']   = $this->input->get('vanue_code') ? $this->input->get('vanue_code') : 'all';
        $data['counter_code']   = $this->input->get('counter_code') ? $this->input->get('counter_code') : 'all';

        if ($data['vanue_code'] == 'all') {
            $vanue_id = 0;
        } else {
            $vanue = $this->Venue_model->get_by_code($data['vanue_code']);
            $vanue_id = $vanue ? $vanue->id : 0;
        }

        if ($data['counter_code'] == 'all') {
            $counter_id = 0;
        } else {
            $this->load->model('Counter_model');
            $counter = $this->Counter_model->get_by_code($data['counter_code']);
            $counter_id = $counter ? $counter->id : 0;
        }

		$data['results'] = $this->Checkin_model->get_all([
            'date_from'     => $data['date_from'],
            'date_to'       => $data['date_to'],
            'vanue_id'      => $vanue_id,
            'counter_id'    => $counter_id,
        ]);

        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            $p->building = $this->Checkin_model->building($p->building_id);
            $p->venue = $this->Checkin_model->venue($p->vanue_id);
            $p->counter = $this->Checkin_model->counter($p->counter_id);

            // morph
            $p->party = $this->Checkin_model->get_party($p);

            // hasMany
            $p->detail = $this->Checkin_model->checkin_detail($p->id);
            foreach ($p->detail as $u) {
                // belongsTo
                $u->ticket = $this->Checkin_Detail_model->ticket($u->ticket_id);
            }

            // belongsTo
            $p->checkin_scan = $this->Checkin_model->checkin_scan($p->id);
            $p->checkin_barcode = $this->Checkin_model->checkin_barcode($p->id);
            $p->checkout = $this->Checkin_model->checkout($p->id);
        }

        $data['venue'] = $this->Venue_model->get_all_is_active(1);
        $data['counters'] = $this->Counter_model->get_all_is_active(1);
        // relation
        foreach ($data['counters'] as $p) {
            // belongsTo
            $p->venue = $this->Counter_model->venue($p->vanue_id);
            $p->venue->building = $this->Venue_model->building($p->venue->area);
        }

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Trans/Checkin/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create($building_code, $venue_code, $counter_code)
	{
		$data['title'] = 'Cashier Ticketing';

		$data['input_type'] = 'create';
        $data['building'] = $this->Building_model->get_by_code($building_code);
        $data['venue'] = $this->Venue_model->get_by_code($venue_code, null);
        $data['counter'] = $this->Counter_model->get_by_code($counter_code, null);
        $data['tax'] = $this->Tax_model->get_last();
        $data['method'] = $this->Common_Code_model->get_by_code('PAYMENT_METHODE');
        $data['building_code'] = $building_code;
        $data['venue_code'] = $venue_code;
        $data['counter_code'] = $counter_code;
        $data['result'] = null;
        $data['detail'] = null;

        // print_rr($data); exit;

        $this->load->view('Tenancy/Ticketing/Trans/Checkin/create', $data);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store($building_code, $venue_code, $counter_code)
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

            redirect('Tenancy/Ticketing/Trans/Checkin/create/'.$building_code.'/'.$venue_code.'/'.$counter_code);
        }

        $this->db->trans_begin();
        try {
            $series = $this->create_series($venue_code, $this->input->post('venue_id'), date('Y-m-d H:i:s'));
            $date = new \Datetime('now');

            $this->Checkin_model->insert([
                'series'  => $series[0],
                'series_code'  => $series[1],
                'date_trans'  => date('Y-m-d'),
                'building_id'  => $this->input->post('building_id'),
                'vanue_id'  => $this->input->post('venue_id'),
                'counter_id'  => $this->input->post('counter_id'),
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

            $seq = 0;
            foreach($this->input->post('cart') as $value){
                $item_id = $value['id'];
                $qty = unformat_number($value['qty']);
                $rate = unformat_number($value['price']);
                $discount = unformat_number($value['discount']);
                $tax = unformat_number($value['tax']);
                // $buy_promotion = unformat_number($value['buy_promotion']);
                // $free_promotion = unformat_number($value['free_promotion']);

                $buy_promotion  = isset($value['buy_promotion']) && $value['buy_promotion'] !== ''
                    ? unformat_number($value['buy_promotion'])
                    : 0;

                $free_promotion = isset($value['free_promotion']) && $value['free_promotion'] !== ''
                    ? unformat_number($value['free_promotion'])
                    : 0;


                // $this->Checkin_Detail_model->insert([
                //     'checkin_id'  => $header_id,
                //     'ticket_id'  => $item_id,
                //     'qty'  => $qty,
                //     'price'  => $rate,
                //     'discount'  => $discount,
                //     'tax'  => $tax,
                //     'is_active'  => 1,
                //     'username'  => $user_id,
                //     'created_at'  => $date->format('Y-m-d H:i:s'),
                //     'updated_at'  => $date->format('Y-m-d H:i:s'),
                // ]);

                $this->Checkin_Detail_model->insert([
                    'checkin_id'      => $header_id,
                    'ticket_id'       => $item_id,
                    'qty'             => $qty,
                    'price'           => $rate,
                    'discount'        => $discount,
                    'tax'             => $tax,
                    'free_promotion'  => $free_promotion, 
                    'is_active'       => 1,
                    'username'        => $user_id,
                    'created_at'      => $date->format('Y-m-d H:i:s'),
                    'updated_at'      => $date->format('Y-m-d H:i:s'),
                ]);


                // get_by_id_category
                $item_ticket = $this->Ticket_model->get_by_id_category($item_id, 'Ticket');
                if ($item_ticket) {
                    if ($buy_promotion > 0 && $buy_promotion != null && $free_promotion > 0 && $free_promotion != null) {
                        if ($qty >= 3) {
                            $qty += 1;
                        }
                    }

                    for ($i=1; $i <= $qty; $i++) {
                        $seq += 1;
                        $this->Checkin_Barcode_model->insert([
                            'checkin_id'  => $header_id,
                            'ticket_id'  => $item_id,
                            'seq'  => $seq,
                            'buy_promotion'  => $buy_promotion,
                            'free_promotion'  => $free_promotion,
                            'is_active'  => 1,
                            'username'  => $user_id,
                            'created_at'  => $date->format('Y-m-d H:i:s'),
                            'updated_at'  => $date->format('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => 'Insert failed, please try again']);
                redirect('Tenancy/Ticketing/Trans/Checkin/create/'.$building_code.'/'.$venue_code.'/'.$counter_code);
            } else {
                $this->db->trans_commit();
                
                $this->session->set_flashdata('flash', ['type' => 'success', 'message' => 'Inserted successfully!']);
                $this->session->set_flashdata('print', ['id' => $header_id, 'is_print' => $this->input->post('is_print')]);

                redirect('Tenancy/Ticketing/Trans/Checkin/create/'.$building_code.'/'.$venue_code.'/'.$counter_code);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            $this->session->set_flashdata('flash', ['type' => 'danger', 'message' => $e->getMessage()]);
            redirect('Tenancy/Ticketing/Trans/Checkin/create/'.$building_code.'/'.$venue_code.'/'.$counter_code);
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
        $this->db->trans_begin();
        try {
            $this->Checkin_Barcode_model->delete_by_checkin_id($id);
            $this->Checkin_Detail_model->delete_by_checkin_id($id);
            $this->Checkin_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Ticketing/Trans/Checkin/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Ticketing/Trans/Checkin/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Trans/Checkin/index');
        }
	}

    public function payment_gate() {
        $data['data'] = $this->input->get('data');
        $data['party_id'] = $this->input->get('party_id');
        $this->load->view('Tenancy/Ticketing/Trans/Checkin/payment_gate', $data);
    }

    // public function print_struk($id) {
    //     $data['judul'] = 'Ticket Billing Receipt';

    //     // billing ****************
    //     $data['checkin'] = $this->Checkin_model->get_by_id($id);
    //     $data['building'] = $this->Building_model->get_by_id($data['checkin']->building_id);
    //     $data['counter'] = $this->Counter_model->get_by_id($data['checkin']->counter_id);
    //     $data['detail'] = $this->Checkin_model->checkin_detail($id);
    //     foreach ($data['detail'] as $u) {
    //         // belongsTo
    //         $u->ticket = $this->Checkin_Detail_model->ticket($u->ticket_id);
    //     }

    //     // person barcode
    //     $data['barcode'] = $this->Checkin_Barcode_model->get_by_checkin_id($id);
    //     foreach ($data['barcode'] as $u) {
    //         // belongsTo
    //         $u->ticket = $this->Checkin_Barcode_model->ticket($u->ticket_id);
    //     }

    //     $data['id'] = $id;
    //     $this->load->view('Tenancy/Ticketing/Trans/Checkin/partials/struk', $data);
    // }

    public function print_struk($id) {
        $data['judul'] = 'Ticket Billing Receipt';

        // billing ****************
        $data['checkin'] = $this->Checkin_model->get_by_id($id);
        $data['building'] = $this->Building_model->get_by_id($data['checkin']->building_id);
        $data['counter'] = $this->Counter_model->get_by_id($data['checkin']->counter_id);
        $data['detail'] = $this->Checkin_model->checkin_detail($id);

        foreach ($data['detail'] as $u) {
            $u->ticket = $this->Checkin_Detail_model->ticket($u->ticket_id);
        }

        // person barcode
        $data['barcode'] = $this->Checkin_Barcode_model->get_by_checkin_id($id);
        foreach ($data['barcode'] as $u) {
            $u->ticket = $this->Checkin_Barcode_model->ticket($u->ticket_id);
        }

        /**
         * FLAG: cek apakah ada ticket type = Entrance
         */
        // $data['has_entrance_ticket'] = false;
        // foreach ($data['detail'] as $d) {
        //     if (!empty($d->ticket->type) && strtolower($d->ticket->type) === 'entrance') {
        //         $data['has_entrance_ticket'] = true;
        //         break;
        //     }
        // }

        $data['non_entrance_qty'] = 0;

        foreach ($data['detail'] as $d) {
            if (!empty($d->ticket->type) && strtolower($d->ticket->type) !== 'entrance') {
                $data['non_entrance_qty'] += (int) $d->qty;
            }
        }

        $data['id'] = $id;
        $this->load->view('Tenancy/Ticketing/Trans/Checkin/partials/struk', $data);
    }

}