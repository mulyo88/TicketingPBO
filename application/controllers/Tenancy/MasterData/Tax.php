<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/MasterData/Tax_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Tax';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Tax_model->get_all();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Tax/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Tax';
		$data['bodyclass'] = 'skin-blue';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Tax/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		if (!$this->input->post()) {
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/MasterData/Tax/create');
		}

        $this->db->trans_begin();
        try {
            $date = new \Datetime('now');
            $this->Tax_model->insert([
                'tax'  => $this->input->post('tax'),
                'date_register'  => date('Y-m-d', strtotime($this->input->post('date_register'))),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/MasterData/Tax/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/MasterData/Tax/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Tax/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Tax';
		$data['bodyclass'] = 'skin-blue';
		
		$data['result'] = $this->Tax_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Tax/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		if (!$this->input->post()) {
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/MasterData/Tax/edit/'.$id);
		}
        
        $this->db->trans_begin();
        try {
            $date = new \Datetime('now');
            $this->Tax_model->update($id, [
                'tax'  => $this->input->post('tax'),
                'date_register'  => date('Y-m-d', strtotime($this->input->post('date_register'))),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/MasterData/Tax/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/MasterData/Tax/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Tax/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Tax_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/MasterData/Tax/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/MasterData/Tax/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Tax/index');
        }
	}
}