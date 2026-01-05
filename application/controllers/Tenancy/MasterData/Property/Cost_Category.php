<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cost_Category extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/MasterData/Property/Cost_Category_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Cost Categories';
		$data['bodyclass'] = 'skin-blue';

		$data['results'] = $this->Cost_Category_model->get_all();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Cost_Category/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Cost Category';
		$data['bodyclass'] = 'skin-blue';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Cost_Category/create', $data);
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
			redirect('Tenancy/MasterData/Property/Cost_Category/create');
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Cost_Category_model->get_by_code_count($this->input->post('code'));
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Property/Cost_Category/create');
            }
            
            $date = new \Datetime('now');
            $this->Cost_Category_model->insert([
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
                redirect('Tenancy/MasterData/Property/Cost_Category/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/MasterData/Property/Cost_Category/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Cost_Category/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Cost Category';
		$data['bodyclass'] = 'skin-blue';
	
		$data['result'] = $this->Cost_Category_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Cost_Category/edit', $data);
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
			redirect('Tenancy/MasterData/Property/Cost_Category/edit/'.$id);
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Cost_Category_model->get_by_code_without_id_count($this->input->post('code'), $id);
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Property/Cost_Category/create');
            }
            
            $date = new \Datetime('now');
            $this->Cost_Category_model->update($id, [
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
                redirect('Tenancy/MasterData/Property/Cost_Category/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/MasterData/Property/Cost_Category/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Cost_Category/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Cost_Category_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/MasterData/Property/Cost_Category/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/MasterData/Property/Cost_Category/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Cost_Category/index');
        }
	}
}