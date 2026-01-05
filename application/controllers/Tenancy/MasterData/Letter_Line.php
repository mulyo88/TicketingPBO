<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Letter_Line extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/MasterData/Letter_Line_model');
        $this->load->model('Tenancy/MasterData/Property/Common_Code_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Letter Lines';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Letter_Line_model->get_all_builder();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Letter_Line/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Letter Line';
		$data['bodyclass'] = 'skin-blue';
		
        $data['common_code'] = $this->Common_Code_model->get_by_code('LETTER_LINE');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Letter_Line/create', $data);
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
			redirect('Tenancy/MasterData/Letter_Line/create');
		}

        $this->db->trans_begin();
        try {
            $date = new \Datetime('now');
            $this->Letter_Line_model->insert([
                'party_id'  => $this->input->post('party_id'),
                'party_type'  => $this->input->post('party_type'),
                'position'  => $this->input->post('position'),
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
                redirect('Tenancy/MasterData/Letter_Line/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/MasterData/Letter_Line/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Letter_Line/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Letter Line';
		$data['bodyclass'] = 'skin-blue';
		
        $data['common_code'] = $this->Common_Code_model->get_by_code('LETTER_LINE');
		$data['result'] = $this->Letter_Line_model->get_by_id($id);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Letter_Line/edit', $data);
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
			redirect('Tenancy/MasterData/Letter_Line/edit/'.$id);
		}
        
        $this->db->trans_begin();
        try {
            $date = new \Datetime('now');
            $this->Letter_Line_model->update($id, [
                'party_id'  => $this->input->post('party_id'),
                'party_type'  => $this->input->post('party_type'),
                'position'  => $this->input->post('position'),
                'name'  => $this->input->post('name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/MasterData/Letter_Line/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/MasterData/Letter_Line/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Letter_Line/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Letter_Line_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/MasterData/Letter_Line/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/MasterData/Letter_Line/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Letter_Line/index');
        }
	}
}