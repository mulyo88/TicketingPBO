<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scan_Com extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{

		$this->load->view('Tenancy/Ticketing/Trans/scan_com');
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