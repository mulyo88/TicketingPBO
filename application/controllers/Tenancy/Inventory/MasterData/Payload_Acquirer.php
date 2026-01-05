<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payload_Acquirer extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
		$this->load->model('Tenancy/Inventory/MasterData/Payload_Acquirer_model');

		$this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
        $this->load->library('encryption');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'Payload Acquirer';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Payload_Acquirer_model->get_all();
        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            $p->payment_gate_name = $this->Payload_Acquirer_model->payment_gate_name($p->payment_gate_id);
            $p->payload_type = $this->Payload_Acquirer_model->payload_type($p->payload_id);
        }

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/MasterData/Payload_Acquirer/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $data['judul'] = 'Create Payload Acquirer';
        $data['bodyclass'] = 'skin-blue';

        $data['payment_gate_name'] = $this->Common_Code_model->get_by_code('PAYMENT_GATE');
        $data['payload_type'] = $this->Common_Code_model->get_by_code('PAYLOAD_ACQUIRER');

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/MasterData/Payload_Acquirer/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('payment_gate_id', 'Payment Gate', 'required|numeric');
        $this->form_validation->set_rules('payload_id', 'Payload Acquirer', 'required|numeric');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/create');
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Payload_Acquirer_model->check_exist($this->input->post('payment_gate_id'));
            if ($exists) {
                is_pesan('error','already exists.');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/create');
            }

            $date = new \Datetime('now');
            $this->Payload_Acquirer_model->insert([
                'payment_gate_id'  => $this->input->post('payment_gate_id'),
                'payload_id'  => $this->input->post('payload_id'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($payment_gate_id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $data['judul'] = 'Edit Payload Acquirer';
        $data['bodyclass'] = 'skin-blue';

        $data['result'] = $this->Payload_Acquirer_model->get_by_id($payment_gate_id);
        $data['payment_gate_name'] = $this->Common_Code_model->get_by_code('PAYMENT_GATE');
        $data['payload_type'] = $this->Common_Code_model->get_by_code('PAYLOAD_ACQUIRER');

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/MasterData/Payload_Acquirer/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($payment_gate_id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('payment_gate_id', 'Payment Gate', 'required|numeric');
        $this->form_validation->set_rules('payload_id', 'Payload Acquirer', 'required|numeric');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/edit/'.$payment_gate_id);
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Payload_Acquirer_model->check_exist_without_id($payment_gate_id, $this->input->post('payment_gate_id'));
            if ($exists) {
                is_pesan('error','already exists.');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/edit/'.$payment_gate_id);
            }

            $date = new \Datetime('now');
            $this->Payload_Acquirer_model->update($payment_gate_id, [
                'payment_gate_id'  => $this->input->post('payment_gate_id'),
                'payload_id'  => $this->input->post('payload_id'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/edit/'.$payment_gate_id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/edit/'.$payment_gate_id);
        }
	}

    public function destroy($payment_gate_id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Payload_Acquirer_model->delete($payment_gate_id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/MasterData/Payload_Acquirer/index');
        }
	}
}