<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalAPI_MasterData extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/MasterData/Property/Unit_Has_Unit_Facility_model');
        $this->load->model('Tenancy/MasterData/Property/Unit_Has_Owner_Property_model');
		$this->load->model('Tenancy/Tenant/Document_model');
		$this->load->model('Tenancy/MasterData/Property/Owner_Property_model');
		$this->load->model('Tenancy/MasterData/Bussiness_model');
		$this->load->model('Tenancy/Tenant/Tenant_model');
		$this->load->model('Tenancy/MasterData/Property/Unit_model');
		$this->load->model('Tenancy/Ticketing/Master/Venue_model');
		$this->load->model('Tenancy/Ticketing/Master/Counter_model');
		$this->load->model('Tenancy/Ticketing/Master/Gate_model');

		$this->load->helper(array('form', 'url', 'file'));
	}

	public function select_unit_by_id($id, $type) {
		if ($type == 'facility') {
			$result = $this->Unit_Has_Unit_Facility_model->get_by_unit_id_builder($id);
		} else if ($type == 'owner') {
			$result = $this->Unit_Has_Owner_Property_model->get_by_unit_id_builder($id);
		} else {
			$result = null;
		}

		echo json_encode($result);
	}

	public function delete_tenant_document_file($tenant_id, $common_id, $filename) {
        $file_path = $this->config->item("tnc_tenant")."/".$tenant_id."/". $filename;

		if (file_exists($file_path)) {
            if (unlink($file_path)) {
				$this->Document_model->delete($tenant_id, $common_id);

                $result = 'File deleted.';
            } else {
                $result = 'Failed file deleted.';
            }
        } else {
            $result = 'File not found.';
        }
		
		echo json_encode($result);
	}

	public function get_party_by_type($type) {
		if ($type == 'BUSSINESS') {
			$result = $this->Bussiness_model->get_all();
		} else if ($type == 'OWNER_PROPERY') {
			$result = $this->Owner_Property_model->get_all();
		} else if ($type == 'TENANT') {
			$result = $this->Tenant_model->get_all();
		}  else {
			$result = null;
		}

		echo json_encode($result);
	}

	public function select_unit_by_building($building_id) {
		$result = $this->Unit_model->get_by_building_id($building_id);

		echo json_encode($result);
	}

	public function load_venue($area) {
		$result = $this->Venue_model->get_by_area($area);
		echo json_encode($result);
	}

	public function load_counter($veneu_code) {
		$venue = $this->Venue_model->get_by_code($veneu_code);
		if ($venue) {
			$venue = $venue->id;
		} else {
			$venue = 0;
		}

		$result = $this->Counter_model->get_all_by_vanue_id($venue);
		echo json_encode($result);
	}

	public function load_gate($veneu_code) {
		$venue = $this->Venue_model->get_by_code($veneu_code);
		if ($venue) {
			$venue = $venue->id;
		} else {
			$venue = 0;
		}

		$result = $this->Gate_model->get_all_by_vanue_id($venue);
		echo json_encode($result);
	}

	public function load_counter_gate($veneu_code) {
		$venue = $this->Venue_model->get_by_code($veneu_code);
		if ($venue) {
			$venue = $venue->id;
		} else {
			$venue = 0;
		}

		$counter = $this->Counter_model->get_all_by_vanue_id($venue);
		$gate = $this->Gate_model->get_all_by_vanue_id($venue);
		echo json_encode([
			'counter' => $counter,
			'gate' => $gate
		]);
	}
}