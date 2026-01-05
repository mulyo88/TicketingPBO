<?php

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalAPI_Trans extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/Inventory/Stock/Item_model');
        $this->load->model('Tenancy/Inventory/Selling/POS_model');
		$this->load->model('Tenancy/Inventory/Selling/POS_Detail_model');
        $this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
        $this->load->model('Tenancy/Inventory/MasterData/Departement_model');
        $this->load->model('Tenancy/Inventory/Buying/Supplier_model');
        $this->load->model('Tenancy/Inventory/Selling/Customer_model');
        $this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/MasterData/Bank_model');

        $this->load->model('Tenancy/Ticketing/Master/Counter_model');
        $this->load->model('Tenancy/Ticketing/Master/Ticket_model');
        $this->load->model('Tenancy/Ticketing/Checkin/Checkin_model');
		$this->load->model('Tenancy/Ticketing/Checkin/Checkin_Detail_model');
        $this->load->model('Tenancy/Ticketing/Checkin/Checkin_Barcode_model');
        $this->load->model('Tenancy/Ticketing/Checkout/Checkout_model');
        $this->load->model('Tenancy/Ticketing/Checkin_Scan/Checkin_Scan_model');

		$this->load->helper(array('form', 'url', 'file', 'qr'));
	}

    public function search_item($search, $search_by, $sort_by, $category) {
        $search = $search == "null" ? "" : $search;
        $search_by = $search_by == "null" ? "" : $search_by;
        $sort_by = $sort_by == "null" ? "" : $sort_by;
        $category = $category == "null" ? "" : $category;

        $result = $this->Item_model->get_by_search($search, $search_by, $sort_by, $category);
        echo json_encode($result);
    }

    public function scan_barcode($code, $area) {
        $result = $this->Item_model->get_by_code_area($code, $area);
        echo json_encode($result);
    }

    public function pos_struk($id) {
        $pos = $this->POS_model->get_by_id($id);
        $building = $this->Building_model->get_by_id($pos->building_id);
        $detail = $this->POS_model->pos_detail($pos->id);
        foreach ($detail as $u) {
            // belongsTo
            $u->item = $this->POS_Detail_model->item($u->item_id);
        }

        echo json_encode(['pos' => $pos, 'detail' => $detail, 'building' => $building]);
    }

    public function get_party($id) {
        $common = $this->Common_Code_model->find($id);
        if ($common) {
            if ($common->note == "BANK") {
                $party = $this->Bank_model->get_all_is_active(1);
                echo json_encode(['model' => $common->note, 'party' => $party]);
            } else if ($common->note == "PAYMENT_GATE") {
                $party = $this->Common_Code_model->get_by_code('PAYMENT_GATE');
                echo json_encode(['model' => $common->note, 'party' => $party]);
            } else {
                echo json_encode(null);
            }
        } else {
            echo json_encode(null);
        }
    }

    public function load_party($id) {
        $common = $this->Common_Code_model->find($id);
        if ($common) {
            if ($common->code == "PARTY" && $common->name == "DEPARTEMENT") {
                $party = $this->Departement_model->get_by_is_active(1);
                echo json_encode(['common' => $common, 'party' => $party]);
            } else if ($common->code == "PARTY" && $common->name == "SUPPLIER") {
                $party = $this->Supplier_model->get_by_is_active(1);
                echo json_encode(['common' => $common, 'party' => $party]);
            } else if ($common->code == "PARTY" && $common->name == "CUSTOMER") {
                $party = $this->Customer_model->get_by_is_active(1);
                echo json_encode(['common' => $common, 'party' => $party]);
            } else if ($common->code == "PARTY" && $common->name == "BUILDING") {
                $party = $this->Building_model->get_by_is_active(1);
                echo json_encode(['common' => $common, 'party' => $party]);
            } else {
                echo json_encode(null);
            }
        } else {
            echo json_encode(null);
        }
    }

    public function load_item($area) {
        $result = $this->Item_model->get_by_area($area);
        echo json_encode($result);
    }

    public function update_stock_all() {
        $this->db->trans_begin();
        try {
            $data = $this->Item_model->get_all_procedure();
            for ($i=0; $i < count($data); $i++) {
                $LedgerNo = $data[$i]->LedgerNo;
                $qty_ending = $data[$i]->ending;

                $this->Item_model->update_stock_ending($LedgerNo, $qty_ending);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                echo json_encode('Update failed, please try again.');
            } else {
                $this->db->trans_commit();

                echo json_encode('Update successfully!');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            echo json_encode($e->getMessage());
        }
    }








    public function load_ticket($vanue_id) {
        // $result = $this->Ticket_model->get_by_vanue($vanue_id);
        $date_from = date('Y-m-d');
        $result = $this->db->query("EXEC dbo.sp_tnc_ticket_item '$date_from', $vanue_id")->result();
        echo json_encode($result);
    }

    public function load_scan_statistic($date_from, $date_to, $building_code, $venue_code, $gate_code, $counter_code, $type) {
        if ($gate_code == 'null') { $gate_code = ''; }
        if ($counter_code == 'null') { $counter_code = ''; }
        if ($type == 'null') { $type = ''; }
        
        $date_from = date('Y-m-d', strtotime($date_from));
        $date_to = date('Y-m-d', strtotime($date_to));

        $query = $this->db->query("EXEC dbo.sp_tnc_ticket_trans_card_statistic '$date_from', '$date_to', '$building_code', '$venue_code', '$gate_code', '$counter_code', '$type'")->result();
        echo json_encode([
            'checkin_statistic' => $query[0]->checkin_statistic,
            'checkin_scan_statistic' => $query[0]->checkin_scan_statistic,
            'checkout_statistic' => $query[0]->checkout_statistic,
            'current_statistic' => $query[0]->current_statistic,
            'gate_statistic' => $query[0]->gate_statistic
        ]);
    }

    function ticketing_checkout_scan_search_barcode() {
        $barcode = $this->input->post('barcode');
        $gate_id = $this->input->post('gate');

        $query = $this->db->query("EXEC dbo.sp_tnc_ticket_trans_search_barcode '$barcode'")->result();
        if (count($query) > 0) {
            if ($query[0]->series == $barcode) {
                echo json_encode(['data' => $query[0], 'msg' => 'not found']);
            } else if ($query[0]->checkout_id) {
                echo json_encode(['data' => $query[0], 'msg' => 'already checkout']);
            } else if (!$query[0]->checkin_scan_id) {
                echo json_encode(['data' => $query[0], 'msg' => 'not yet checkin']);
            } else {
                $this->insert_checkout_scan(
                    $query[0]->id,
                    $gate_id,
                    $query[0]->ticket_id,
                    $query[0]->seq,
                );

                echo json_encode(['data' => $query[0], 'msg' => 'scan success']);
            }
        } else {
            echo json_encode(['data' => null, 'msg' => 'not found']);
        }
    }

    public function insert_checkout_scan($checkin_id, $gate_id, $ticket_id, $seq) {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $date = new \Datetime('now');

        $this->Checkout_model->insert([
            'date_trans'  => date('Y-m-d'),
            'checkin_id'  => $checkin_id,
            'gate_id'  => $gate_id,
            'ticket_id'  => $ticket_id,
            'seq'  => $seq,
            'is_active'  => 1,
            'username'  => $user_id,
            'created_at'  => $date->format('Y-m-d H:i:s'),
            'updated_at'  => $date->format('Y-m-d H:i:s'),
        ]);

        return;
    }


    public function checkin_struk($id) {
        // billing ****************
        $checkin = $this->Checkin_model->get_by_id($id);
        $building = $this->Building_model->get_by_id($checkin->building_id);
        $counter = $this->Counter_model->get_by_id($checkin->counter_id);
        $detail = $this->Checkin_model->checkin_detail($checkin->id);
        foreach ($detail as $u) {
            // belongsTo
            $u->ticket = $this->Checkin_Detail_model->ticket($u->ticket_id);
        }

        // person barcode
        $barcode = $this->Checkin_Barcode_model->get_by_checkin_id($checkin->id);

        echo json_encode(['billing' => [
            'checkin' => $checkin,
            'detail' => $detail,
            'building' => $building,
            'counter' => $counter
        ], 'barcode' => [
            'data' => $barcode
        ]]);
    }


    function ticketing_checkin_scan_search_barcode() {
        $barcode = $this->input->post('barcode');
        $gate_id = $this->input->post('gate');

        $query = $this->db->query("EXEC dbo.sp_tnc_ticket_trans_search_barcode '$barcode'")->result();
        if (count($query) > 0) {
            if ($query[0]->series == $barcode) {
                echo json_encode(['data' => $query[0], 'msg' => 'not found']);
            } else if ($query[0]->checkout_id) {
                echo json_encode(['data' => $query[0], 'msg' => 'already checkout']);
            } else if ($query[0]->checkin_scan_id) {
                echo json_encode(['data' => $query[0], 'msg' => 'already checkin']);
            } else {
                $this->insert_checkin_scan(
                    $query[0]->id,
                    $gate_id,
                    $query[0]->ticket_id,
                    $query[0]->seq,
                );

                echo json_encode(['data' => $query[0], 'msg' => 'scan success']);
            }
        } else {
            echo json_encode(['data' => null, 'msg' => 'not found']);
        }
    }

    public function insert_checkin_scan($checkin_id, $gate_id, $ticket_id, $seq) {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $date = new \Datetime('now');

        $this->Checkin_Scan_model->insert([
            'date_trans'  => date('Y-m-d'),
            'checkin_id'  => $checkin_id,
            'gate_id'  => $gate_id,
            'ticket_id'  => $ticket_id,
            'seq'  => $seq,
            'is_active'  => 1,
            'username'  => $user_id,
            'created_at'  => $date->format('Y-m-d H:i:s'),
            'updated_at'  => $date->format('Y-m-d H:i:s'),
        ]);

        return;
    }








    public function report_stock_datalist($date_trans, $area, $departement, $category, $code, $name, $stock) {
        $date_trans = date('Y-m-d', strtotime($date_trans));
        $area = $area == 'null' ? '' : $area;
        $departement = $departement == 'null' ? '' : $departement;
        $category = $category == 'null' ? '' : $category;
        $code = $code == 'null' ? '' : $code;
        $name = $name == 'null' ? '' : $name;

        $query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_datalist '$date_trans', '$area', '$departement', '$category', '$code', '$name', '$stock'");
        echo json_encode($query->result());
    }

    public function report_stock_daily($date_trans, $area, $departement, $category, $code, $name, $seq_uom) {
        $date_trans = date('Y-m-d', strtotime($date_trans));
        $area = $area == 'null' ? '' : $area;
        $departement = $departement == 'null' ? '' : $departement;
        $category = $category == 'null' ? '' : $category;
        $code = $code == 'null' ? '' : $code;
        $name = $name == 'null' ? '' : $name;

        $query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_daily '$date_trans', '$area', '$departement', '$category', '$code', '$name', $seq_uom");
        echo json_encode($query->result());
    }

    public function report_stock_monthly($date_trans, $area, $departement, $category, $code, $name, $seq_uom) {
        $date_trans = date('Y-m-d', strtotime($date_trans));
        $area = $area == 'null' ? '' : $area;
        $departement = $departement == 'null' ? '' : $departement;
        $category = $category == 'null' ? '' : $category;
        $code = $code == 'null' ? '' : $code;
        $name = $name == 'null' ? '' : $name;

        $query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_monthly '$date_trans', '$area', '$departement', '$category', '$code', '$name', $seq_uom");
        echo json_encode($query->result());
    }

    public function report_stock_yearly($date_trans, $area, $departement, $category, $code, $name, $seq_uom) {
        $date_trans = date('Y-m-d', strtotime($date_trans));
        $area = $area == 'null' ? '' : $area;
        $departement = $departement == 'null' ? '' : $departement;
        $category = $category == 'null' ? '' : $category;
        $code = $code == 'null' ? '' : $code;
        $name = $name == 'null' ? '' : $name;

        $query = $this->db->query("EXEC dbo.sp_tnc_inven_r_stock_yearly '$date_trans', '$area', '$departement', '$category', '$code', '$name', $seq_uom");
        echo json_encode($query->result());
    }

    function encrypt_qr() {
        $scanned = $this->input->post('qr');
        if (!$scanned) {
            echo json_encode(['status' => 'failed', 'data' => 'QR code not provided!']);
            return;
        }

        $encrypt = encryptData(urldecode($scanned));
        echo json_encode(['status' => 'success', 'data' => $encrypt]);
    }

    function descript_qr() {
        $scanned = $this->input->post('qr');
        if (!$scanned) {
            echo json_encode(['status' => 'failed', 'data' => 'QR code not provided!']);
            return;
        }

        $decrypted = decryptData(urldecode($scanned));

        if ($decrypted === false) {
            echo json_encode(['status' => 'failed', 'data' => 'QR code invalid or manipulated!']);
        } else {
            echo json_encode(['status' => 'success', 'data' => $decrypted]);
        }
    }
}