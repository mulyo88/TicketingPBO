<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/Ticketing/Master/Venue_model');
        $this->load->model('Tenancy/Ticketing/Master/Ticket_model');
        $this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/Ticketing/Master/Common_Code_model');
        $this->load->model('Tenancy/Ticketing/Master/Price_model');

        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$data['judul'] = "Tickets";
		$data['bodyclass'] = 'skin-blue';

		// $data['results'] = $this->Ticket_model->get_all();
        // // relation
        // foreach ($data['results'] as $p) {
        //     // belongsTo
        //     $p->venue = $this->Ticket_model->venue($p->vanue_id);
        //     $p->venue->building = $this->Venue_model->building($p->venue->area);
        //     $p->prices = $this->Ticket_model->price($p->id);
        // }

        $data['results'] = $this->db->query("EXEC dbo.sp_tnc_ticket_load")->result();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Ticket/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
        $data['judul'] = 'Create Ticket';
        $data['bodyclass'] = 'skin-blue';
		
        $data['building'] = $this->Building_model->get_by_is_active(1);
        $data['category'] = $this->Common_Code_model->get_by_code('TICKET_CATEGORY');
        $data['type'] = $this->Common_Code_model->get_by_code('TICKET_TYPE');
        $data['party'] = $this->Common_Code_model->get_by_code('TICKET_PARTY');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Ticket/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('vanue_id', 'Location', 'required');
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        
        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
            is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Ticketing/Master/Ticket/create');
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Ticket_model->check_exists(
                [
                    'vanue_id'  => $this->input->post('vanue_id'),
                    'category'  => $this->input->post('category'),
                    'name'  => $this->input->post('name'),
                ]
            );
            
            if ($exists) {
                is_pesan('error','name : '.$this->input->post('name').'  already exists.');
                redirect('Tenancy/Ticketing/Master/Ticket/create');
            }
            
            $date = new \Datetime('now');
            $this->Ticket_model->insert([
                'vanue_id'  => $this->input->post('vanue_id'),
                'category'  => $this->input->post('category'),
                'type'  => $this->input->post('type'),
                'party'  => $this->input->post('party'),
                'name'  => $this->input->post('name'),
                'price'  => $this->input->post('price'),
                'note'  => $this->input->post('note'),
                'tax'  => $this->input->post('tax') ? 1 : 0,
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            $header_id = $this->db->insert_id();
            $this->store_price($header_id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Ticket/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Ticketing/Master/Ticket/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Ticket/create');
        }
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store_price($header_id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        if ($header_id == null) {
            return;
        }
        
        $date = new \Datetime('now');
        $this->Price_model->delete($header_id);
        foreach ($this->input->post('xprice') as $day => $value) {
            $day_name = ucfirst($day);
            $amount = $value['amount'];
            $discount = $value['discount'];
            $buy_ticket = $value['buy'];
            $free_ticket = $value['free'];
            $is_active = $value['is_active'];

            if ($amount == '') { $amount = 0; }
            if ($discount == '') { $discount = 0; }
            if ($buy_ticket == '') { $buy_ticket = 0; }
            if ($free_ticket == '') { $free_ticket = 0; }
            if ($is_active == '') { $is_active = 0; }
            
            $this->Price_model->insert([
                'ticket_id'  => $header_id,
                'day_name'  => $day_name,
                'amount'  => $amount,
                'discount'  => $discount,
                'buy_ticket'  => $buy_ticket,
                'free_ticket'  => $free_ticket,
                'is_active'  => $is_active ? 1 : 0,
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
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
		$data['judul'] = 'Edit Ticket';
		$data['bodyclass'] = 'skin-blue';
		
        $data['result'] = $this->Ticket_model->find($id);
        $data['result']->venue = $this->Ticket_model->venue($data['result']->vanue_id);
        $data['result']->xprice = $this->Ticket_model->price($data['result']->id);

		$data['building'] = $this->Building_model->get_by_is_active(1);
        $data['category'] = $this->Common_Code_model->get_by_code('TICKET_CATEGORY');
        $data['type'] = $this->Common_Code_model->get_by_code('TICKET_TYPE');
        $data['party'] = $this->Common_Code_model->get_by_code('TICKET_PARTY');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Ticketing/Master/Ticket/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->form_validation->set_rules('vanue_id', 'Location', 'required');
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');

        $this->session->set_flashdata('_old_input', $this->input->post());
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
    
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Ticketing/Master/Ticket/edit/'.$id);
        }

        $this->db->trans_begin();
        try {
            $exists = $this->Ticket_model->check_exists(
                [
                    'vanue_id'  => $this->input->post('vanue_id'),
                    'category'  => $this->input->post('category'),
                    // 'name'  => $this->input->post('name'),
                ], $id,
            );
            if ($exists) {
                is_pesan('error','name : '.$this->input->post('name').' already exists.');
                redirect('Tenancy/Ticketing/Master/Ticket/edit/'.$id);
            }
            
            $date = new \Datetime('now');
            $this->Ticket_model->update($id, [
                'vanue_id'  => $this->input->post('vanue_id'),
                'category'  => $this->input->post('category'),
                'type'  => $this->input->post('type'),
                'party'  => $this->input->post('party'),
                'name'  => $this->input->post('name'),
                'price'  => $this->input->post('price'),
                'note'  => $this->input->post('note'),
                'tax'  => $this->input->post('tax') ? 1 : 0,
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            $this->store_price($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Ticket/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Ticketing/Master/Ticket/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Ticket/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $this->db->trans_begin();
        try {
            $this->Price_model->delete($id);
            $this->Ticket_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Ticketing/Master/Ticket/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Ticketing/Master/Ticket/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Ticketing/Master/Ticket/index');
        }
	}
}