<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_Report extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Tenancy/Ticketing/Master/Venue_model');
        $this->load->model('Tenancy/Ticketing/Master/Common_Code_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = 'Daily Report';
		$data['bodyclass'] = 'skin-blue';
		
        // $first_date = date('Y-m-01');
        // $last_date = date('Y-m-t');

        $first_date = null;
        $last_date = null;

        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['vanue_code']   = $this->input->get('vanue_code') ? $this->input->get('vanue_code') : 'all';
        $data['category']   = $this->input->get('category') ? $this->input->get('category') : 'all';

        $query = "EXEC dbo.sp_tnc_ticket_r_daily ?, ?, ?, ?";
        $data['results'] = $this->db->query($query, array($data['date_from'], $data['date_to'], $data['vanue_code'], $data['category']))->result();
    
        $data['venue'] = $this->Venue_model->get_all_is_active(1);
        $data['categories'] = $this->Common_Code_model->get_by_code('TICKET_CATEGORY');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Report/Daily_Report', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		
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

    public function destroy($id)
	{

	}
}