<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/MasterData/Property/Unit_model');
        $this->load->model('Tenancy/MasterData/Property/Unit_Facility_model');
        $this->load->model('Tenancy/MasterData/Property/Owner_Property_model');
        $this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/MasterData/Property/Unit_Has_Unit_Facility_model');
        $this->load->model('Tenancy/MasterData/Property/Unit_Has_Owner_Property_model');
        $this->load->model('Tenancy/Contract/New_Contract_model');
        $this->load->model('Tenancy/Tenant/Tenant_model');
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Unit Types';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Unit_model->get_all_builder();
        // relation
        foreach ($data['results'] as $p) {
            // belongsTo
            if ($p->contract_id) {
                $p->contract = $this->New_Contract_model->find($p->contract_id);
                $p->contract->tenant = $this->Tenant_model->find($p->contract->tenant_id);
            } else {
                $p->contract = null;
            }
        }

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Unit/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Unit Type';
		$data['bodyclass'] = 'skin-blue';
	
        $data['unit_facility'] = $this->Unit_Facility_model->get_by_is_active(1);
        $data['owner'] = $this->Owner_Property_model->get_by_is_active(1);
        $data['unit'] = $this->Unit_model->get_all_builder();
        $data['buildings'] = $this->Building_model->get_by_is_active(1);

        $data['tnc_mp_unit_has_unit_facility'] = null;
        $data['tnc_mp_unit_has_owner_property'] = null;

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Unit/create', $data);
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
			redirect('Tenancy/MasterData/Property/Unit/create');
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Unit_model->get_by_code_count($this->input->post('code'));
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Property/Unit/create');
            }
            
            $date = new \Datetime('now');
            $this->Unit_model->insert([
                'code'  => $this->input->post('code'),
                'name'  => $this->input->post('name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'building_id'  => $this->input->post('building_id'),
                'unit_type'  => $this->input->post('unit_type'),
                'unit_size'  => $this->input->post('unit_size'),
                'basic_price'  => $this->input->post('basic_price'),
                'username'  => $user_id,
                'created_at'  => $date->format('Y-m-d H:i:s'),
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);
            $unit_id = $this->db->insert_id();

            if ($this->input->post('facility')) {
                foreach($this->input->post('facility') as $value){
                    $unit_facility_id = $value['facility_id'];
                    $unit_facility_qty = $value['facility_qty'];

                    $this->Unit_Has_Unit_Facility_model->insert([
                        'unit_id'  => $unit_id,
                        'unit_facility_id'  => $unit_facility_id,
                        'qty'  => $unit_facility_qty,
                        'username'  => $user_id,
                        'created_at'  => $date->format('Y-m-d H:i:s'),
                        'updated_at'  => $date->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($this->input->post('owner')) {
                foreach($this->input->post('owner') as $value){
                    $owner_id = $value['owner_id'];
                    
                    $this->Unit_Has_Owner_Property_model->insert([
                        'unit_id'  => $unit_id,
                        'owner_property_id'  => $owner_id,
                        'username'  => $user_id,
                        'created_at'  => $date->format('Y-m-d H:i:s'),
                        'updated_at'  => $date->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/MasterData/Property/Unit/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/MasterData/Property/Unit/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Unit/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Edit Unit Type';
		$data['bodyclass'] = 'skin-blue';
		
        $data['unit_facility'] = $this->Unit_Facility_model->get_by_is_active(1);
        $data['owner'] = $this->Owner_Property_model->get_by_is_active(1);
        $data['unit'] = $this->Unit_model->get_all_builder();
        $data['buildings'] = $this->Building_model->get_by_is_active(1);
		$data['result'] = $this->Unit_model->get_by_id($id);

        $data['tnc_mp_unit_has_unit_facility'] =  $this->Unit_Has_Unit_Facility_model->get_by_unit_id_builder($id);
        $data['tnc_mp_unit_has_owner_property'] =  $this->Unit_Has_Owner_Property_model->get_by_unit_id_builder($id);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/MasterData/Property/Unit/edit', $data);
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
			redirect('Tenancy/MasterData/Property/Unit/edit/'.$id);
		}

        $this->db->trans_begin();
        try {
            $exists = $this->Unit_model->get_by_code_without_id_count($this->input->post('code'), $id);
            if ($exists > 0 ) {
                is_pesan('error','code : '.$this->input->post('code').' already exists.');
                redirect('Tenancy/MasterData/Property/Unit/create');
            }
            
            $date = new \Datetime('now');
            $this->Unit_model->update($id, [
                'code'  => $this->input->post('code'),
                'name'  => $this->input->post('name'),
                'note'  => $this->input->post('note'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
                'building_id'  => $this->input->post('building_id'),
                'unit_type'  => $this->input->post('unit_type'),
                'unit_size'  => $this->input->post('unit_size'),
                'basic_price'  => $this->input->post('basic_price'),
                'username'  => $user_id,
                'updated_at'  => $date->format('Y-m-d H:i:s'),
            ]);

            $this->Unit_Has_Unit_Facility_model->delete($id);
            $this->Unit_Has_Owner_Property_model->delete($id);

            if ($this->input->post('facility')) {
                foreach($this->input->post('facility') as $value){
                    $unit_facility_id = $value['facility_id'];
                    $unit_facility_qty = $value['facility_qty'];

                    $this->Unit_Has_Unit_Facility_model->insert([
                        'unit_id'  => $id,
                        'unit_facility_id'  => $unit_facility_id,
                        'qty'  => $unit_facility_qty,
                        'username'  => $user_id,
                        'created_at'  => $date->format('Y-m-d H:i:s'),
                        'updated_at'  => $date->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($this->input->post('owner')) {
                foreach($this->input->post('owner') as $value){
                    $owner_id = $value['owner_id'];
                    $this->Unit_Has_Owner_Property_model->insert([
                        'unit_id'  => $id,
                        'owner_property_id'  => $owner_id,
                        'username'  => $user_id,
                        'created_at'  => $date->format('Y-m-d H:i:s'),
                        'updated_at'  => $date->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/MasterData/Property/Unit/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/MasterData/Property/Unit/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Unit/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $this->Unit_Has_Unit_Facility_model->delete($id);
            $this->Unit_Has_Owner_Property_model->delete($id);
            $this->Unit_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/MasterData/Property/Unit/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/MasterData/Property/Unit/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/MasterData/Property/Unit/index');
        }
	}
}