<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Measure extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/Inventory/MasterData/Measure_model');

        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = "UOM's (Unit of Measures)";
		$data['bodyclass'] = 'skin-blue';

		$data['results'] = $this->Measure_model->get_all();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/MasterData/Measure/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'Create UOM (Unit of Measure)';
        $data['bodyclass'] = 'skin-blue';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/MasterData/Measure/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('name', 'Name', 'required');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Inventory/MasterData/Measure/create');
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Measure_model->get_by_name_count($this->input->post('name'));
            if ($exists > 0 ) {
                is_pesan('error','name : '.$this->input->post('name').'  already exists.');
                redirect('Tenancy/Inventory/MasterData/Measure/create');
            }
            
            $date = new \Datetime('now');
            $this->Measure_model->insert([
                'name'  => $this->input->post('name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Inventory/MasterData/Measure/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Inventory/MasterData/Measure/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/MasterData/Measure/create');
        }
	}

    /**
     * Display the specified resource.
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit UOM (Unit of Measure)';
		$data['bodyclass'] = 'skin-blue';
		
		$data['result'] = $this->Measure_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/MasterData/Measure/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('name', 'Name', 'required');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Inventory/MasterData/Measure/edit/'.$id);
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Measure_model->get_by_name_without_id_count($this->input->post('name'), $id);
            if ($exists > 0 ) {
                is_pesan('error','name : '.$this->input->post('name').' already exists.');
                redirect('Tenancy/Inventory/MasterData/Measure/edit/'.$id);
            }
            
            $date = new \Datetime('now');
            $this->Measure_model->update($id, [
                'name'  => $this->input->post('name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Inventory/MasterData/Measure/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Inventory/MasterData/Measure/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/MasterData/Measure/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Measure_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Inventory/MasterData/Measure/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Inventory/MasterData/Measure/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/MasterData/Measure/index');
        }
	}
}