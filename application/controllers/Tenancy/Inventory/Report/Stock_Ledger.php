<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_Ledger extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Building_model');
		$this->load->model('Tenancy/Inventory/MasterData/Departement_model');
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = 'Stock Ledger';
		$data['bodyclass'] = 'skin-blue';
		
		$data['building'] = $this->Building_model->get_by_is_active(1);
		$data['departement'] = $this->Departement_model->get_by_is_active(1);
		$data['category'] = $this->Common_Code_model->get_by_code('Category');
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Report/Stock_Ledger/index', $data);
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