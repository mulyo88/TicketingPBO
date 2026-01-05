<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_Ledger extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Tenancy/Ticketing/Master/Venue_model');
        $this->load->model('Tenancy/Ticketing/Master/Common_Code_model');
	}

    /**
     * Display a listing of the resource.
     */
	// public function index()
	// {
	// 	$data['judul'] = 'Report Ticket Ledger';
	// 	$data['bodyclass'] = 'skin-blue';
		
    //     $first_date = date('Y-m-01');
    //     $last_date = date('Y-m-t');

    //     $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
    //     $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
    //     // $data['area_code']   = $this->input->get('area_code') ? $this->input->get('area_code') : 'all';
    //     $data['vanue_code']   = $this->input->get('vanue_code') ? $this->input->get('vanue_code') : 'all';
    //     $data['counter_code']   = $this->input->get('counter_code') ? $this->input->get('counter_code') : 'all';
    //     $data['gate_code']   = $this->input->get('gate_code') ? $this->input->get('gate_code') : 'all';
    //     $data['gate_checkout']   = $this->input->get('gate_checkout') ? $this->input->get('gate_checkout') : 'all';
    //     $data['barcode']   = $this->input->get('barcode') ? $this->input->get('barcode') : '';
    //     $data['is_checkout']   = $this->input->get('is_checkout') ? $this->input->get('is_checkout') : null;
    //     $data['category']   = $this->input->get('category') ? $this->input->get('category') : 'all';

    //     if ($data['is_checkout']) {
    //         if ($data['is_checkout'] == 'all') {
    //             $is_checkout = 0;
    //         } else if ($data['is_checkout'] == 'checkout') {
    //             $is_checkout = 1;
    //         } else if ($data['is_checkout'] == 'not_yet') {
    //             $is_checkout = 2;
    //         }

    //         // $query = "EXEC dbo.sp_tnc_ticket_r_ledger ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
    //         $query = "EXEC dbo.sp_tnc_ticket_r_ledger ?, ?, ?, ?, ?, ?, ?, ?";
    //         $data['results'] = $this->db->query(
    //             $query,
    //             array(
    //                 $data['date_from'],      // @date_from
    //                 $data['date_to'],        // @date_to
    //                 'all',                   // @area_code (sementara)
    //                 $data['vanue_code'],     // @vanue_code
    //                 $data['counter_code'],   // @counter_code
    //                 $data['gate_code'],      // @gate_code
    //                 $data['barcode'],        // @barcode
    //                 $is_checkout,             // @is_checkout
    //             )
    //         )->result();
    //         // $data['results'] = $this->db->query($query, array($data['date_from'], $data['date_to'],  $data['vanue_code'], $data['counter_code'], $data['gate_code'], $data['gate_checkout'], $data['barcode'], $is_checkout, $data['category']))->result();
    //     } else {
    //         $data['results'] = null;
            

    //     }
        
    //     $data['venue'] = $this->Venue_model->get_all_is_active(1);
    //     $data['categories'] = $this->Common_Code_model->get_by_code('TICKET_CATEGORY');

	// 	$this->load->view('templates/header', $data);
	// 	$this->load->view('templates/sidebar');
	// 	$this->load->view('Tenancy/Ticketing/Report/Ticket_Ledger', $data);
	// 	$this->load->view('templates/footer');
	// }

    public function index()
{
	$data['judul'] = 'Report Ticket Ledger';
	$data['bodyclass'] = 'skin-blue';

	$first_date = date('Y-m-01');
	$last_date  = date('Y-m-t');

	$data['date_from']    = $this->input->get('date_from') ?? $first_date;
	$data['date_to']      = $this->input->get('date_to') ?? $last_date;
	$data['vanue_code']   = $this->input->get('vanue_code') ?? 'all';
	$data['counter_code'] = $this->input->get('counter_code') ?? 'all';
	$data['gate_code']    = $this->input->get('gate_code') ?? 'all';
	$data['gate_checkout']= $this->input->get('gate_checkout') ?? 'all';
	$data['barcode']      = $this->input->get('barcode') ?? '';
	$data['category']     = $this->input->get('category') ?? 'all';

	$is_checkout_param = $this->input->get('is_checkout');

	if ($is_checkout_param === 'checkout') {
		$is_checkout = 1;
	} elseif ($is_checkout_param === 'not_yet') {
		$is_checkout = 2;
	} else {
		$is_checkout = 0;
	}

	$query = "EXEC dbo.sp_tnc_ticket_r_ledger ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

	$data['results'] = $this->db->query($query, [
		$data['date_from'],
		$data['date_to'],
		'all',                    // area_code (sementara)
		$data['vanue_code'],
		$data['counter_code'],
		$data['gate_code'],
		$data['gate_checkout'],
		$data['barcode'],
		$is_checkout,
		$data['category'],
	])->result();

	$data['venue'] = $this->Venue_model->get_all_is_active(1);
	$data['categories'] = $this->Common_Code_model->get_by_code('TICKET_CATEGORY');

	$this->load->view('templates/header', $data);
	$this->load->view('templates/sidebar');
	$this->load->view('Tenancy/Ticketing/Report/Ticket_Ledger', $data);
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