<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tenant extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/Tenant/Tenant_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Tenant';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Tenant_model->get_all();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Tenant/Tenant/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Tenant';
		$data['bodyclass'] = 'skin-blue';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Tenant/Tenant/create', $data);
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
			redirect('Tenancy/Tenant/Tenant/create');
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Tenant_model->get_by_code_count($this->input->post('code'));
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/Tenant/Tenant/create');
            }
            
            $date = new \Datetime('now');
            $this->Tenant_model->insert([
                'code'  => $this->input->post('code'),
                'owner'  => $this->input->post('owner'),
                'brand'  => $this->input->post('brand'),
                'product'  => $this->input->post('product'),
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
                redirect('Tenancy/Tenant/Tenant/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Tenant/Tenant/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Tenant/Tenant/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Tenant';
		$data['bodyclass'] = 'skin-blue';
		
		$data['result'] = $this->Tenant_model->get_by_id($id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Tenant/Tenant/edit', $data);
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
			redirect('Tenancy/Tenant/Tenant/edit/'.$id);
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Tenant_model->get_by_code_without_id_count($this->input->post('code'), $id);
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/Tenant/Tenant/create');
            }
            
            $date = new \Datetime('now');
            $this->Tenant_model->update($id, [
                'code'  => $this->input->post('code'),
                'owner'  => $this->input->post('owner'),
                'brand'  => $this->input->post('brand'),
                'product'  => $this->input->post('product'),
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
                redirect('Tenancy/Tenant/Tenant/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Tenant/Tenant/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Tenant/Tenant/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Tenant_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Tenant/Tenant/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Tenant/Tenant/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Tenant/Tenant/index');
        }
	}
}