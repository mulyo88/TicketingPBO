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
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = 'Report - Incoming';
		$data['bodyclass'] = 'skin-blue';
		
		$data['building'] = $this->Building_model->get_all();
		
		$first_date = date('Y-m-01');
        $last_date = date('Y-m-t');
        
        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['area']       = $this->input->get('area');
        $data['item_code']  = $this->input->get('item_code');
        $data['item_name']  = $this->input->get('item_name');
        $data['userlogin']  = $this->input->get('userlogin');
        $data['mode']  		= $this->input->get('mode') ? $this->input->get('mode') : 'Simple';
        $data['type']  		= $this->input->get('type') ? $this->input->get('type') : 'DataList';
		
		$building_id        = $this->Building_model->find_code($data['area']);

		if ($data['type'] == 'DataList' && $data['mode'] == 'Simple') {
			$data['results'] = $this->inquery_datalist_simple($data['date_from'], $data['date_to'], $building_id, $data['item_code'], $data['item_name'], $data['userlogin']);
		} else if ($data['type'] == 'DataList' && $data['mode'] == 'Detail') {
			$data['results'] = $this->inquery_datalist_detail($data['date_from'], $data['date_to'], $data['area'], $data['item_code'], $data['item_name'], $data['userlogin']);
		} else if ($data['type'] == 'Daily') {
			$data['results'] = $this->inquery_datalist_daily($data['date_from'], $data['date_to'], $data['area'], $data['item_code'], $data['item_name'], $data['userlogin']);
		} else if ($data['type'] == 'Monthly') {
			$data['results'] = $this->inquery_datalist_monthly($data['date_from'], $data['date_to'], $data['area'], $data['item_code'], $data['item_name'], $data['userlogin']);
		} else if ($data['type'] == 'Yearly') {
			$data['results'] = $this->inquery_datalist_yearly($data['date_from'], $data['date_to'], $data['area'], $data['item_code'], $data['item_name'], $data['userlogin']);
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Report/Incoming/index', $data);
		$this->load->view('templates/footer');
	}

	private function inquery_datalist_simple($date_from, $date_to, $building_id, $item_code, $item_name, $userlogin) {
		$query = $this->Incoming_model->get_all([
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'building_id'   => $building_id ? $building_id->id : null,
            'userlogin'   	=> $userlogin,
        ]);

        // relation
        foreach ($query as $p) {
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
        foreach ($query as $p) {
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

        $query = $filtered;
		return $query;
	}

	private function inquery_datalist_detail($date_from, $date_to, $area, $item_code, $item_name, $userlogin) {
		if ($area == 'all') { $area = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_incoming_datalist '$date_from', '$date_to', '$area', '$item_code', '$item_name', '$userlogin'");
		return $query->result();
	}

    private function inquery_datalist_daily($date_from, $date_to, $area, $item_code, $item_name, $userlogin) {
		if ($area == 'all') { $area = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_incoming_daily '$date_from', '$date_to', '$area', '$item_code', '$item_name', '$userlogin'");
		return $query->result();
	}

	private function inquery_datalist_monthly($date_from, $date_to, $area, $item_code, $item_name, $userlogin) {
		if ($area == 'all') { $area = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_incoming_monthly '$date_from', '$date_to', '$area', '$item_code', '$item_name', '$userlogin'");
		return $query->result();
	}

	private function inquery_datalist_yearly($date_from, $date_to, $area, $item_code, $item_name, $userlogin) {
		if ($area == 'all') { $area = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_incoming_yearly '$date_from', '$date_to', '$area', '$item_code', '$item_name', '$userlogin'");
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