<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/Ticketing/Master/Venue_model');
        $this->load->model('Tenancy/Ticketing/Master/Gate_model');
        $this->load->model('Tenancy/MasterData/Property/Building_model');

        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = "Gate's";
		$data['bodyclass'] = 'skin-blue';

		$data['results'] = $this->Gate_model->get_all();
        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            $p->venue = $this->Gate_model->venue($p->vanue_id);
            $p->venue->building = $this->Venue_model->building($p->venue->area);
        }

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Gate/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
        $data['judul'] = 'Create Gate';
        $data['bodyclass'] = 'skin-blue';
		
        $data['building'] = $this->Building_model->get_by_is_active(1);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Gate/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('vanue_id', 'Location', 'required');
        $this->form_validation->set_rules('code', 'Code', 'required');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Ticketing/Master/Gate/create');
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Gate_model->check_exists(
                [
                    'vanue_id'  => $this->input->post('vanue_id'),
                    'code'      => $this->input->post('code'),
                ]
            );
            
            if ($exists) {
                is_pesan('error','code : '.$this->input->post('code').'  already exists.');
                redirect('Tenancy/Ticketing/Master/Gate/create');
            }
            
            $date = new \Datetime('now');
            $this->Gate_model->insert([
                'vanue_id'  => $this->input->post('vanue_id'),
                'code'  => $this->input->post('code'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Gate/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Ticketing/Master/Gate/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Gate/create');
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
		$data['judul'] = 'Edit Gate';
		$data['bodyclass'] = 'skin-blue';
		
        $data['result'] = $this->Gate_model->find($id);
        $data['result']->venue = $this->Gate_model->venue($data['result']->vanue_id);

		$data['building'] = $this->Building_model->get_by_is_active(1);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Gate/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('vanue_id', 'Location', 'required');
        $this->form_validation->set_rules('code', 'Code', 'required');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Ticketing/Master/Gate/edit/'.$id);
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Gate_model->check_exists(
                [
                    'vanue_id'  => $this->input->post('vanue_id'),
                    'code'      => $this->input->post('code'),
                ], $id,
            );
            if ($exists) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/Ticketing/Master/Gate/edit/'.$id);
            }
            
            $date = new \Datetime('now');
            $this->Gate_model->update($id, [
                'vanue_id'  => $this->input->post('vanue_id'),
                'code'  => $this->input->post('code'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Gate/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Ticketing/Master/Gate/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Gate/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $this->db->trans_begin();
        try {
            $this->Gate_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Gate/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Ticketing/Master/Gate/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Gate/index');
        }
	}
}