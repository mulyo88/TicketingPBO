<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_Code extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/Ticketing/Master/Common_Code_model');

        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Common Codes';
		$data['bodyclass'] = 'skin-blue';

		$data['results'] = $this->Common_Code_model->get_all_builder();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Common_Code/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create($code)
	{
        if ($code == null || $code == '' || $code == '0') {
            $data['judul'] = 'Create Common Code';
        } else {
            $data['judul'] = 'Create ' . $code;
        }

        $data['bodyclass'] = 'skin-blue';
		
        $data['code'] = $code;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Common_Code/create', $data);
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
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Ticketing/Master/Common_Code/create');
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Common_Code_model->get_by_code_count($this->input->post('code'));
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' and name : '.$name.'  already exists.');
                redirect('Tenancy/Ticketing/Master/Common_Code/create/'.$this->input->post('code'));
            }
            
            $date = new \Datetime('now');
            $this->Common_Code_model->insert([
                'code'  => $this->input->post('code'),
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
                redirect('Tenancy/Ticketing/Master/Common_Code/create/'.$this->input->post('code'));
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Ticketing/Master/Common_Code/show/'.$this->input->post('code'));
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Common_Code/create/'.$this->input->post('code'));
        }
	}

    /**
     * Display the specified resource.
     */
    public function show($code)
    {
		$data['judul'] = 'Create Common Code - ' . $code;
		$data['bodyclass'] = 'skin-blue';
		
        $data['code'] = $code;
		$data['results'] = $this->Common_Code_model->get_by_code($code);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Common_Code/show', $data);
		$this->load->view('templates/footer');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $code)
	{
		$data['judul'] = 'Edit Common Code - ' . $code;
		$data['bodyclass'] = 'skin-blue';
		
        $data['code'] = $code;
		$data['result'] = $this->Common_Code_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Common_Code/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id, $code)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('code', 'Code', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
            redirect('Tenancy/Ticketing/Master/Common_Code/edit/'.$id.'/'.$code);
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Common_Code_model->get_by_code_without_id_count($code, $id);
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/Ticketing/Master/Common_Code/edit/'.$id.'/'.$code);
            }
            
            $date = new \Datetime('now');
            $this->Common_Code_model->update($id, [
                'code'  => $this->input->post('code'),
                'name'  => $this->input->post('name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Common_Code/edit/'.$id.'/'.$code);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Ticketing/Master/Common_Code/show/'.$code);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Common_Code/edit/'.$id.'/'.$code);
        }
	}

    public function destroy($id, $code)
	{
        $this->db->trans_begin();
        try {
            $this->Common_Code_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Common_Code/show/'.$code);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Ticketing/Master/Common_Code/show/'.$code);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Common_Code/show/'.$code);
        }
	}
}