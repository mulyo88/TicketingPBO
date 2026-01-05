<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settlement extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Building_model');
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
		$this->load->model('Tenancy/Inventory/Selling/POS_model');
		$this->load->model('Tenancy/Inventory/Selling/POS_Detail_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = 'Report - Settlement';
		$data['bodyclass'] = 'skin-blue';
		
		$data['building'] = $this->Building_model->get_all();
		$data['methods'] = $this->Common_Code_model->get_by_code('PAYMENT_METHODE');
		
		$first_date = date('Y-m-01 00:00');
        $last_date = date('Y-m-t 00:00');
        
        $data['date_from']  = $this->input->get('date_from') ? $this->input->get('date_from') : $first_date;
        $data['date_to']    = $this->input->get('date_to') ? $this->input->get('date_to') : $last_date;
        $data['area']       = $this->input->get('area') ? $this->input->get('area') : '';
        $data['method']     = $this->input->get('method') ? $this->input->get('method') : '';

		$building_id        = $this->Building_model->find_code($data['area']);
		
		if ($this->input->get('area')) {
			$data['results'] = $this->report_settlement($data['date_from'], $data['date_to'], $data['area'], $data['method']);

			// summary relation
			foreach ($data['results']->summary as $p) {
				// belongsTo
				$p->building = $this->POS_model->building($p->building_id);
			}

			// transaction relation
			foreach ($data['results']->transaction as $p) {
				// belongsTo
				$p->building = $this->POS_model->building($p->building_id);
				// morph
				$p->party = $this->POS_model->get_party($p);
				// hasMany
				$p->detail = $this->POS_model->pos_detail($p->id);
				foreach ($p->detail as $u) {
					// belongsTo
					$u->item = $this->POS_Detail_model->item($u->item_id);
				}
			}

			// var_dump($data['results']);
			// exit;
		} else {
			$data['results'] = null;
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Report/Settlement/index', $data);
		$this->load->view('templates/footer');
	}

	// multi select stored procedure (important - by ario)
	private function report_settlement($date_from, $date_to, $area, $method)
    {
		$date_from = date('Y-m-d H:i:s', strtotime($date_from));
		$date_to = date('Y-m-d H:i:s', strtotime($date_to));

		if ($area == 'all') {
			$area = '';
		}

		if ($method == 'all') {
			$method = '';
		}

        $conn = $this->db->conn_id;
        $sql = "{CALL sp_tnc_inven_r_settlement(?, ?, ?, ?)}";
        $params = array($date_from, $date_to, $area, $method);

        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $result_set = [];
        $index = 0;

        do {
            $rows = [];
			while ($row = sqlsrv_fetch_object($stmt)) {
                $rows[] = $row;
            }
            $result_set[$index] = $rows;
            $index++;

        } while (sqlsrv_next_result($stmt));

        sqlsrv_free_stmt($stmt);
		return (object) [
            'summary' => $result_set[0] ?? [],
            'transaction'  => $result_set[1] ?? [],
            'datalist'  => $result_set[2] ?? [],
        ];
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