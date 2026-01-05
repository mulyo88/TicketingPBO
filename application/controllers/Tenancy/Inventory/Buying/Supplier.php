<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/Inventory/Buying/Supplier_model');

        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Suppliers';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Supplier_model->get_all();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Buying/Supplier/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Supplier';
		$data['bodyclass'] = 'skin-blue';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Buying/Supplier/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('code', 'Code', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('telp', 'Telp', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Inventory/Buying/Supplier/create');
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Supplier_model->get_by_code($this->input->post('code'));
            if ($exists) {
                is_pesan('error','code : '.$this->input->post('code').'  already exists.');
                redirect('Tenancy/Inventory/Buying/Supplier/create');
            }

            $date = new \Datetime('now');
            $this->Supplier_model->insert([
                'code'  => $this->input->post('code'),
                'name'  => $this->input->post('name'),
                'address'  => $this->input->post('address'),
                'telp'  => $this->input->post('telp'),
                'email'  => $this->input->post('email'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Inventory/Buying/Supplier/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Inventory/Buying/Supplier/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Buying/Supplier/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Supplier';
		$data['bodyclass'] = 'skin-blue';
		
		$data['result'] = $this->Supplier_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Inventory/Buying/Supplier/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('code', 'Code', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('telp', 'Telp', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Inventory/Buying/Supplier/edit/'.$id);
        }
        
        $this->db->trans_begin();
        try {
            $exists = $this->Supplier_model->get_by_code_without_id($this->input->post('code'), $id);
            if ($exists ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/Inventory/Buying/Supplier/edit/'.$id);
            }

            $date = new \Datetime('now');
            $this->Supplier_model->update($id, [
                'code'  => $this->input->post('code'),
                'name'  => $this->input->post('name'),
                'address'  => $this->input->post('address'),
                'telp'  => $this->input->post('telp'),
                'email'  => $this->input->post('email'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Inventory/Buying/Supplier/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Inventory/Buying/Supplier/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Buying/Supplier/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Supplier_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Inventory/Buying/Supplier/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Inventory/Buying/Supplier/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Inventory/Buying/Supplier/index');
        }
	}
}