<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Tenancy/Ticketing/Master/Common_Code_model');
		$this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/Ticketing/Master/Venue_model');
        $this->load->model('Tenancy/Ticketing/Master/Ticket_model');
        $this->load->model('Tenancy/Ticketing/Master/Counter_model');
        $this->load->model('Tenancy/Ticketing/Master/Gate_model');
		$this->load->model('Tenancy/Ticketing/Checkin/Checkin_model');
		$this->load->model('Tenancy/Ticketing/Checkin/Checkin_Detail_model');
		$this->load->model('Tenancy/Ticketing/Checkin/Checkin_Barcode_model');
		$this->load->model('Tenancy/Ticketing/Checkout/Checkout_model');

		$this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = 'Checkout Ticketing';
		$data['bodyclass'] = 'skin-blue';
		
        $first_date = date('Y-m-01');
        $last_date = date('Y-m-t');

        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['vanue_code']   = $this->input->get('vanue_code') ? $this->input->get('vanue_code') : 'all';
        $data['gate_code']   = $this->input->get('gate_code') ? $this->input->get('gate_code') : 'all';

        $query = "EXEC dbo.sp_tnc_ticket_trans_checkout_load ?, ?, ?, ?";
        $data['results'] = $this->db->query($query, array($data['date_from'], $data['date_to'], $data['vanue_code'], $data['gate_code']))->result();
        
        $data['venue'] = $this->Venue_model->get_all_is_active(1);
        $data['gates'] = $this->Gate_model->get_all_is_active(1);
        // relation
        foreach ($data['gates'] as $p) {
            // belongsTo
            $p->venue = $this->Gate_model->venue($p->vanue_id);
            $p->venue->building = $this->Venue_model->building($p->venue->area);
        }
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Trans/Checkout/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create($building_code, $venue_code, $gate_code)
	{
		$data['title'] = 'Checkout Ticketing';

		$data['input_type'] = 'create';
        $data['building'] = $this->Building_model->get_by_code($building_code);
        $data['venue'] = $this->Venue_model->get_by_code($venue_code, null);
        $data['gate'] = $this->Gate_model->get_by_code($gate_code, null);
        $data['building_code'] = $building_code;
        $data['venue_code'] = $venue_code;
        $data['gate_code'] = $gate_code;
        $data['result'] = null;
        $data['detail'] = null;

        $data['json'] = $this->Common_Code_model->get_by_code_note('JSON', $gate_code);
        $data['com'] = $this->Common_Code_model->get_by_code_note('COM_SERIAL', $gate_code);

        $this->load->view('Tenancy/Ticketing/Trans/Checkout/create', $data);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        
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

    public function destroy($checkin_id, $ticket_id, $seq, $gate_id)
	{
        $this->db->trans_begin();
        try {
            $this->Checkout_model->delete($checkin_id, $ticket_id, $seq, $gate_id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Ticketing/Trans/Checkout/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Ticketing/Trans/Checkout/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Trans/Checkout/index');
        }
	}
}