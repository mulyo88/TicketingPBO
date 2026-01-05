<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_Entry extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Building_model');
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = 'Report - Stock Entries';
		$data['bodyclass'] = 'skin-blue';
	
        $first_date = date('Y-m-01');
        $last_date = date('Y-m-t');
        
        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['area']       = $this->input->get('area') == '' ? 'all' : $this->input->get('area');
        $data['item_code']  = $this->input->get('item_code');
        $data['item_name']  = $this->input->get('item_name');
        $data['status']     = $this->input->get('status') == '' ? 'all' : $this->input->get('status');
        $data['username']  	= $this->input->get('username');
        $data['type']  		= $this->input->get('type') ? $this->input->get('type') : 'DataList';
		
        $data['building']   = $this->Building_model->get_all();
        $data['status_x']   = $this->Common_Code_model->get_by_code('STATUS_STOCK_ENTRY');

        if ($data['type'] == 'DataList') {
			$data['results'] = $this->inquery_datalist($data['date_from'], $data['date_to'], $data['area'], $data['status'], $data['item_code'], $data['item_name'], $data['username']);
		} else if ($data['type'] == 'Daily') {
			$data['results'] = $this->inquery_datalist_daily($data['date_from'], $data['date_to'], $data['area'], $data['status'], $data['item_code'], $data['item_name'], $data['username']);
		} else if ($data['type'] == 'Monthly') {
			$data['results'] = $this->inquery_datalist_monthly($data['date_from'], $data['date_to'], $data['area'], $data['status'], $data['item_code'], $data['item_name'], $data['username']);
		} else if ($data['type'] == 'Yearly') {
			$data['results'] = $this->inquery_datalist_yearly($data['date_from'], $data['date_to'], $data['area'], $data['status'], $data['item_code'], $data['item_name'], $data['username']);
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Report/Stock_Entry/index', $data);
		$this->load->view('templates/footer');
	}

	private function inquery_datalist($date_from, $date_to, $area, $status, $item_code, $item_name, $username) {
		if ($area == 'all') { $area = ''; }
		if ($status == 'all') { $status = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_entry_datalist '$date_from', '$date_to', '$area', '$status', '$item_code', '$item_name', '$username'");
		return $query->result();
	}

    private function inquery_datalist_daily($date_from, $date_to, $area, $status, $item_code, $item_name, $username) {
		if ($area == 'all') { $area = ''; }
		if ($status == 'all') { $status = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_entry_daily '$date_from', '$date_to', '$area', '$status', '$item_code', '$item_name', '$username'");
		return $query->result();
	}

	private function inquery_datalist_monthly($date_from, $date_to, $area, $status, $item_code, $item_name, $username) {
		if ($area == 'all') { $area = ''; }
		if ($status == 'all') { $status = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_entry_monthly '$date_from', '$date_to', '$area', '$status', '$item_code', '$item_name', '$username'");
		return $query->result();
	}

	private function inquery_datalist_yearly($date_from, $date_to, $area, $status, $item_code, $item_name, $username) {
		if ($area == 'all') { $area = ''; }
		if ($status == 'all') { $status = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_entry_yearly '$date_from', '$date_to', '$area', '$status', '$item_code', '$item_name', '$username'");
		return $query->result();
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