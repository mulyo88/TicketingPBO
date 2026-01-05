<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/MasterData/Bank_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Banks';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Bank_model->get_all();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Bank/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Bank';
		$data['bodyclass'] = 'skin-blue';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Bank/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $this->session->set_flashdata('_old_input', $this->input->post());
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		if (!$this->input->post()) {
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/MasterData/Bank/create');
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Bank_model->get_by_code($this->input->post('code'));
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Bank/create');
            }
            
            $date = new \Datetime('now');
            $this->Bank_model->insert([
                'account_code'  => $this->input->post('account_code'),
                'account_name'  => $this->input->post('account_name'),
                'owner_name'  => $this->input->post('owner_name'),
                'office_name'  => $this->input->post('office_name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/MasterData/Bank/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/MasterData/Bank/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Bank/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Bank';
		$data['bodyclass'] = 'skin-blue';
		
		$data['result'] = $this->Bank_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Bank/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $this->session->set_flashdata('_old_input', $this->input->post());
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		if (!$this->input->post()) {
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/MasterData/Bank/edit/'.$id);
		}
        
        $this->db->trans_begin();
        try {
            $exists = $this->Bank_model->get_by_code_without_id($this->input->post('code'), $id);
            if ($exists) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Bank/create');
            }
            
            $date = new \Datetime('now');
            $this->Bank_model->update($id, [
                'account_code'  => $this->input->post('account_code'),
                'account_name'  => $this->input->post('account_name'),
                'owner_name'  => $this->input->post('owner_name'),
                'office_name'  => $this->input->post('office_name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/MasterData/Bank/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/MasterData/Bank/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Bank/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Bank_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/MasterData/Bank/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/MasterData/Bank/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Bank/index');
        }
	}
}