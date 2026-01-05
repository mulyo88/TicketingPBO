<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_Contract extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/MasterData/Property/Common_Code_model');
        $this->load->model('Tenancy/Tenant/Tenant_model');
        $this->load->model('Tenancy/Tenant/Document_model');
        $this->load->model('Tenancy/MasterData/Bank_model');
        $this->load->model('Tenancy/MasterData/Tax_model');
        $this->load->model('Tenancy/Contract/New_Contract_model');
        $this->load->model('Tenancy/Contract/New_Contract_Detail_model');
        $this->load->model('Tenancy/MasterData/Bussiness_model');

        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    private function create_series($code, $date)
    {
        $format = $this->Common_Code_model->get_by_code_last('SERIES_LOO');
        if ($format) {
            $counter = $this->New_Contract_model->get_series($format->name, $date)->total + 1;
            $month = $this->symbol_number(date('m', strtotime($date)));
            $year = date('Y', strtotime($date));
            $series = $this->formatZero($counter) . $format->name .$code . '/' .$month . '/' .$year;
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

    private function setFullDatetime($time)
    {
        $date = date('Y-m-d');
        $datetime_string = $date . ' ' . $time;
        $timestamp = strtotime($datetime_string);

        return date('Y-m-d H:i:s', $timestamp);
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
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'New Contracts LOO';
		$data['bodyclass'] = 'skin-blue';

        // Common_Code_model
        $data['loo_docs'] = $this->Common_Code_model->get_by_code('DOCUMENT_LOO');
        $data['results'] = $this->New_Contract_model->get_all();

        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            $p->building = $this->New_Contract_model->building($p->id);
            $p->tenant = $this->New_Contract_model->tenant($p->id);
            // hasMany
            $p->documents = $this->Tenant_model->document($p->tenant_id);
            foreach ($p->documents as $u) {
                // belongsTo
                $u->common = $this->Document_model->common_code($u->common_id);
            }

            // hasMany
            $p->details = $this->New_Contract_model->contract_detail($p->id);
            foreach ($p->details as $u) {
                // belongsTo
                $u->unit = $this->New_Contract_Detail_model->unit($u->unit_id);
            }
        }

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Contract/New_Contract/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'Create New Contract LOO';
		$data['bodyclass'] = 'skin-blue';
		
        $data['input_type'] = 'create';
        $data['building'] = $this->Building_model->get_all();
        $data['tenant'] = $this->Tenant_model->get_all();
        $data['bank'] = $this->Bank_model->get_all();
        $data['tax'] = $this->Tax_model->get_last();
        $data['result'] = null;
        
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Contract/New_Contract/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('building_name', 'Building', 'required');
        $this->form_validation->set_rules('tenant_name', 'Tenant', 'required');
        $this->form_validation->set_rules('bank_name', 'Bank', 'required');
        $this->form_validation->set_rules('unit_qty', 'Unit Qty', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('month_period', 'Month period', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('total_payment', 'Payment', 'required|callback_not_zero');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
            redirect('Tenancy/Contract/New_Contract/create');
        }

        $this->db->trans_begin();
        try {
            $series = $this->create_series($this->input->post('tenant_code'), date('Y-m-d H:i:s'));
            $buss = $this->Bussiness_model->get_last();
            $date = new \Datetime('now');

            $this->New_Contract_model->insert([
                'series'  => $series[0],
                'series_code'  => $series[1],
                'date_trans'  => date('Y-m-d', strtotime($this->input->post('date_trans'))),
                'building_id'  => $this->input->post('building_id'),
                'bussiness_id'  => $buss->id,
                'tenant_id'  => $this->input->post('tenant_id'),
                'letter_from_id'  => 0,
                'letter_to_id'  => 0,
                'unit_qty'  => unformat_number($this->input->post('unit_qty')),
                'unit_total_per_item'  => unformat_number($this->input->post('unit_total_per_item')),
                'unit_total_per_month'  => unformat_number($this->input->post('unit_total_per_month')),
                'unit_total_per_grand'  => unformat_number($this->input->post('unit_total_per_grand')),
                'charge_total_per_item'  => unformat_number($this->input->post('charge_total_per_item')),
                'charge_total_per_month'  => unformat_number($this->input->post('charge_total_per_month')),
                'charge_total_per_grand'  => unformat_number($this->input->post('charge_total_per_grand')),
                'without_tax_total_per_item'  => unformat_number($this->input->post('without_tax_total_per_item')),
                'without_tax_total_per_month'  => unformat_number($this->input->post('without_tax_total_per_month')),
                'without_tax_total_per_grand'  => unformat_number($this->input->post('without_tax_total_per_grand')),
                'with_tax_total_per_item'  => unformat_number($this->input->post('with_tax_total_per_item')),
                'with_tax_total_per_month'  => unformat_number($this->input->post('with_tax_total_per_month')),
                'with_tax_total_per_grand'  => unformat_number($this->input->post('with_tax_total_per_grand')),
                'tax'  => unformat_number($this->input->post('tax')),
                'total_indoor'  => unformat_number($this->input->post('total_indoor')),
                'total_outdoor'  => unformat_number($this->input->post('total_outdoor')),
                'charge_indoor'  => unformat_number($this->input->post('charge_indoor')),
                'charge_outdoor'  => unformat_number($this->input->post('charge_outdoor')),
                'down_payment'  => unformat_number($this->input->post('down_payment')),
                'security_deposite'  => unformat_number($this->input->post('security_deposite')),
                'fitting_out'  => unformat_number($this->input->post('fitting_out')),
                'month_period'  => unformat_number($this->input->post('month_period')),
                'charge_outdoor'  => unformat_number($this->input->post('charge_outdoor')),
                'tenant_start_date_operation'  => date('Y-m-d', strtotime($this->input->post('tenant_start_date_operation'))),
                'tenant_end_date_operation'  => date('Y-m-d', strtotime($this->input->post('tenant_end_date_operation'))),
                'building_operation'  => $this->input->post('building_operation'),
                'tenant_start_hour_operation'  => $this->setFullDatetime($this->input->post('tenant_start_hour_operation')),
                'tenant_end_hour_operation'  => $this->setFullDatetime($this->input->post('tenant_end_hour_operation')),
                'bank_id'  => $this->input->post('bank_id'),
                'is_active'  => 1,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
            $contract_id = $this->db->insert_id();

            foreach($this->input->post('unit') as $value){
                $unit_id = $value['unit_id'];
                $unit_size = unformat_number($value['unit_size']);
                $unit_rate = unformat_number($value['unit_price']);
                $charge_rate = unformat_number($value['charge_rate']);
                $unit_discount = unformat_number($value['unit_discount']);
                $charge_discount = unformat_number($value['charge_discount']);
                $tax = unformat_number($this->input->post('tax'));
                $unit_total = unformat_number($value['unit_after_discount']);
                $charge_total = unformat_number($value['charge_after_discount']);

                $this->New_Contract_Detail_model->insert([
                    'type_item'  => 'Unit',
                    'contract_id'  => $contract_id,
                    'unit_id'  => $unit_id,
                    'unit_size'  => $unit_size,
                    'rate'  => $unit_rate,
                    'discount'  => $unit_discount,
                    'tax'  => $tax,
                    'total'  => $unit_total,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);

                $this->New_Contract_Detail_model->insert([
                    'type_item'  => 'Charge',
                    'contract_id'  => $contract_id,
                    'unit_id'  => $unit_id,
                    'unit_size'  => $unit_size,
                    'rate'  => $charge_rate,
                    'discount'  => $charge_discount,
                    'tax'  => $tax,
                    'total'  => $charge_total,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Contract/New_Contract/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Contract/New_Contract/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Contract/New_Contract/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
		
        $data['input_type'] = 'edit';
        $data['building'] = $this->Building_model->get_all();
        $data['tenant'] = $this->Tenant_model->get_all();
        $data['bank'] = $this->Bank_model->get_all();
        $data['tax'] = $this->Tax_model->get_last();

        $data['result'] = $this->New_Contract_model->get_by_id($id);
        // belongsTo
        $data['result']->bank = $this->New_Contract_model->bank($data['result']->bank_id);
        $data['result']->building = $this->New_Contract_model->building($data['result']->id);
        $data['result']->tenant = $this->New_Contract_model->tenant($data['result']->id);
        // hasMany
        $data['result']->details = $this->New_Contract_model->contract_detail_edit($data['result']->id);
        foreach ($data['result']->details as $u) {
            // belongsTo
            $u->unit = $this->New_Contract_Detail_model->unit($u->unit_id);
        }

		$data['judul'] = $data['result']->series;
		$data['bodyclass'] = 'skin-blue';

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Contract/New_Contract/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('building_name', 'Building', 'required');
        $this->form_validation->set_rules('tenant_name', 'Tenant', 'required');
        $this->form_validation->set_rules('bank_name', 'Bank', 'required');
        $this->form_validation->set_rules('unit_qty', 'Unit Qty', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('month_period', 'Month period', 'required|numeric|callback_not_zero');
        $this->form_validation->set_rules('total_payment', 'Payment', 'required|callback_not_zero');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
            redirect('Tenancy/Contract/New_Contract/edit/'.$id);
        }
        
        $this->db->trans_begin();
        try {
            $date = new \Datetime('now');
            $this->New_Contract_model->update($id, [
                'date_trans'  => date('Y-m-d', strtotime($this->input->post('date_trans'))),
                'building_id'  => $this->input->post('building_id'),
                'tenant_id'  => $this->input->post('tenant_id'),
                'unit_qty'  => unformat_number($this->input->post('unit_qty')),
                'unit_total_per_item'  => unformat_number($this->input->post('unit_total_per_item')),
                'unit_total_per_month'  => unformat_number($this->input->post('unit_total_per_month')),
                'unit_total_per_grand'  => unformat_number($this->input->post('unit_total_per_grand')),
                'charge_total_per_item'  => unformat_number($this->input->post('charge_total_per_item')),
                'charge_total_per_month'  => unformat_number($this->input->post('charge_total_per_month')),
                'charge_total_per_grand'  => unformat_number($this->input->post('charge_total_per_grand')),
                'without_tax_total_per_item'  => unformat_number($this->input->post('without_tax_total_per_item')),
                'without_tax_total_per_month'  => unformat_number($this->input->post('without_tax_total_per_month')),
                'without_tax_total_per_grand'  => unformat_number($this->input->post('without_tax_total_per_grand')),
                'with_tax_total_per_item'  => unformat_number($this->input->post('with_tax_total_per_item')),
                'with_tax_total_per_month'  => unformat_number($this->input->post('with_tax_total_per_month')),
                'with_tax_total_per_grand'  => unformat_number($this->input->post('with_tax_total_per_grand')),
                'tax'  => unformat_number($this->input->post('tax')),
                'total_indoor'  => unformat_number($this->input->post('total_indoor')),
                'total_outdoor'  => unformat_number($this->input->post('total_outdoor')),
                'charge_indoor'  => unformat_number($this->input->post('charge_indoor')),
                'charge_outdoor'  => unformat_number($this->input->post('charge_outdoor')),
                'down_payment'  => unformat_number($this->input->post('down_payment')),
                'security_deposite'  => unformat_number($this->input->post('security_deposite')),
                'fitting_out'  => unformat_number($this->input->post('fitting_out')),
                'month_period'  => unformat_number($this->input->post('month_period')),
                'charge_outdoor'  => unformat_number($this->input->post('charge_outdoor')),
                'tenant_start_date_operation'  => date('Y-m-d', strtotime($this->input->post('tenant_start_date_operation'))),
                'tenant_end_date_operation'  => date('Y-m-d', strtotime($this->input->post('tenant_end_date_operation'))),
                'building_operation'  => $this->input->post('building_operation'),
                'tenant_start_hour_operation'  => $this->setFullDatetime($this->input->post('tenant_start_hour_operation')),
                'tenant_end_hour_operation'  => $this->setFullDatetime($this->input->post('tenant_end_hour_operation')),
                'bank_id'  => $this->input->post('bank_id'),
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            $this->New_Contract_Detail_model->delete_by_contract_id($id);
            foreach($this->input->post('unit') as $value){
                $unit_id = $value['unit_id'];
                $unit_size = unformat_number($value['unit_size']);
                $unit_rate = unformat_number($value['unit_price']);
                $charge_rate = unformat_number($value['charge_rate']);
                $unit_discount = unformat_number($value['unit_discount']);
                $charge_discount = unformat_number($value['charge_discount']);
                $tax = unformat_number($this->input->post('tax'));
                $unit_total = unformat_number($value['unit_after_discount']);
                $charge_total = unformat_number($value['charge_after_discount']);

                $this->New_Contract_Detail_model->insert([
                    'type_item'  => 'Unit',
                    'contract_id'  => $id,
                    'unit_id'  => $unit_id,
                    'unit_size'  => $unit_size,
                    'rate'  => $unit_rate,
                    'discount'  => $unit_discount,
                    'tax'  => $tax,
                    'total'  => $unit_total,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);

                $this->New_Contract_Detail_model->insert([
                    'type_item'  => 'Charge',
                    'contract_id'  => $id,
                    'unit_id'  => $unit_id,
                    'unit_size'  => $unit_size,
                    'rate'  => $charge_rate,
                    'discount'  => $charge_discount,
                    'tax'  => $tax,
                    'total'  => $charge_total,
                    'is_active'  => 1,
                    'username'  => $user_id,
                    'created_at'  => $date->format('Y-m-d H:i:s'),
                    'updated_at'  => $date->format('Y-m-d H:i:s'),
                ]);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Contract/New_Contract/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Contract/New_Contract/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Contract/New_Contract/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->New_Contract_Detail_model->delete_by_contract_id($id);
            $this->New_Contract_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Contract/New_Contract/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Contract/New_Contract/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Contract/New_Contract/index');
        }
	}
}