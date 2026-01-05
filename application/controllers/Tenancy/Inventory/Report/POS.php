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
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = 'Report - Point of Sales';
		$data['bodyclass'] = 'skin-blue';
		
		$data['building'] = $this->Building_model->get_all();
		$data['methods'] = $this->Common_Code_model->get_by_code('PAYMENT_METHODE');
		$data['area_color'] = $this->Common_Code_model->get_by_code('AREA_COLOR');
		
		$first_date = date('Y-m-01');
        $last_date = date('Y-m-t');
        
        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['area']       = $this->input->get('area');
        $data['method']     = $this->input->get('method');
        $data['item_code']  = $this->input->get('item_code');
        $data['item_name']  = $this->input->get('item_name');
        $data['cashier']  	= $this->input->get('cashier');
        // $data['mode']  		= $this->input->get('mode') ? $this->input->get('mode') : 'Simple';
        // $data['type']  		= $this->input->get('type') ? $this->input->get('type') : 'DataList';
		
		$data['mode']  		= $this->input->get('mode') ? $this->input->get('mode') : null;
        $data['type']  		= $this->input->get('type') ? $this->input->get('type') : null;

		$building_id        = $this->Building_model->find_code($data['area']);

		if ($data['type']) {
			if ($data['type'] == 'DataList' && $data['mode'] == 'Simple') {
				$data['results'] = $this->inquery_datalist_simple($data['date_from'], $data['date_to'], $building_id, $data['method'], $data['cashier']);
			} else if ($data['type'] == 'DataList' && $data['mode'] == 'Detail') {
				$data['results'] = $this->inquery_datalist_detail($data['date_from'], $data['date_to'], $data['area'], $data['method'], $data['item_code'], $data['item_name'], $data['cashier']);
			} else if ($data['type'] == 'Daily') {
				$data['results'] = $this->inquery_datalist_daily($data['date_from'], $data['date_to'], $data['area'], $data['method'], $data['item_code'], $data['item_name'], $data['cashier']);
			} else if ($data['type'] == 'Monthly') {
				$data['results'] = $this->inquery_datalist_monthly($data['date_from'], $data['date_to'], $data['area'], $data['method'], $data['item_code'], $data['item_name'], $data['cashier']);
			} else if ($data['type'] == 'Yearly') {
				$data['results'] = $this->inquery_datalist_yearly($data['date_from'], $data['date_to'], $data['area'], $data['method'], $data['item_code'], $data['item_name'], $data['cashier']);
			}
		} else {
			$data['results'] = null;
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Report/POS/index', $data);
		$this->load->view('templates/footer');
	}

	private function inquery_datalist_simple($date_from, $date_to, $building_id, $method, $cashier) {
		$query = $this->POS_model->get_all([
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'building_id'   => $building_id ? $building_id->id : null,
            'method'   		=> $method,
			'cashier'   	=> $cashier,
        ]);
        // relation
        foreach ($query as $p) {
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

		return $query;
	}

	private function inquery_datalist_detail($date_from, $date_to, $area, $method, $item_code, $item_name, $cashier) {
		if ($area == 'all') { $area = ''; }
		if ($method == 'all') { $method = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_pos_datalist '$date_from', '$date_to', '$area', '$method', '$item_code', '$item_name', '$cashier'");
		return $query->result();
	}

	// multi select stored procedure (important - by ario)
    private function inquery_datalist_daily($date_from, $date_to, $area, $method, $item_code, $item_name, $cashier) {
		if ($area == 'all') { $area = ''; }
		if ($method == 'all') { $method = ''; }

		// $query = $this->db->query("EXEC dbo.sp_tnc_inven_r_pos_daily '$date_from', '$date_to', '$area', '$method', '$item_code', '$item_name', '$cashier'");
		// return $query->result();
	
		// $sql = "EXEC sp_tnc_inven_r_pos_daily ?, ?, ?, ?, ?, ?, ?";
		// $params = [$date_from, $date_to, $area, $method, $item_code, $item_name, $cashier];
		
		// $stmt = sqlsrv_query($this->db->conn_id, $sql, $params);
		
		$query = "EXEC dbo.sp_tnc_inven_r_pos_daily '$date_from', '$date_to', '$area', '$method', '$item_code', '$item_name', '$cashier'";
		$stmt = sqlsrv_query($this->db->conn_id, $query);

		$result1 = [];
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$result1[] = $row;
		}
		
		if (sqlsrv_next_result($stmt)) {
			$result2 = [];
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$result2[] = $row;
			}
		}
		
		return [
			'data' => $result1,
			'chart'  => $result2
		];
	}

	private function inquery_datalist_monthly($date_from, $date_to, $area, $method, $item_code, $item_name, $cashier) {
		if ($area == 'all') { $area = ''; }
		if ($method == 'all') { $method = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_pos_monthly '$date_from', '$date_to', '$area', '$method', '$item_code', '$item_name', '$cashier'");
		return $query->result();
	}

	private function inquery_datalist_yearly($date_from, $date_to, $area, $method, $item_code, $item_name, $cashier) {
		if ($area == 'all') { $area = ''; }
		if ($method == 'all') { $method = ''; }

		$query = $this->db->query("EXEC dbo.sp_tnc_inven_r_pos_yearly '$date_from', '$date_to', '$area', '$method', '$item_code', '$item_name', '$cashier'");
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