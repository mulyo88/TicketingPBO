<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Building extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/MasterData/Property/Building_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Buildings';
		$data['bodyclass'] = 'skin-blue';

		$data['results'] = $this->Building_model->get_all();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Building/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Building';
		$data['bodyclass'] = 'skin-blue';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Building/create', $data);
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
			redirect('Tenancy/MasterData/Property/Building/create');
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Building_model->get_by_code_count($this->input->post('code'));
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Property/Building/create');
            }
            
            $date = new \Datetime('now');
            $this->Building_model->insert([
                'code'  => $this->input->post('code'),
                'name'  => $this->input->post('name'),
                'operation_time'  => $this->input->post('operation_time'),
                'address'  => $this->input->post('address'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/MasterData/Property/Building/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/MasterData/Property/Building/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Building/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Building';
		$data['bodyclass'] = 'skin-blue';
		
		$data['result'] = $this->Building_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Building/edit', $data);
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
			redirect('Tenancy/MasterData/Property/Building/edit/'.$id);
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Building_model->get_by_code_without_id_count($this->input->post('code'), $id);
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Property/Building/edit/'.$id);
            }
            
            $date = new \Datetime('now');
            $this->Building_model->update($id, [
                'code'  => $this->input->post('code'),
                'name'  => $this->input->post('name'),
                'operation_time'  => $this->input->post('operation_time'),
                'address'  => $this->input->post('address'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/MasterData/Property/Building/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/MasterData/Property/Building/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Building/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Building_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/MasterData/Property/Building/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/MasterData/Property/Building/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Building/index');
        }
	}
}