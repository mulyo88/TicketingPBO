<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalAPI_Payment_Gate extends CI_Controller {
    private $x_building_id = null;
    private $x_value = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tenancy/Inventory/MasterData/Payment_Gate_Key_model');
        $this->load->model('Tenancy/Inventory/MasterData/Common_Code_model');
        $this->load->model('Tenancy/Inventory/MasterData/Payload_Acquirer_model');
		$this->load->model('Tenancy/MasterData/Property/Building_model');
        $this->load->model('Tenancy/MasterData/Bank_model');
        
        $this->load->library('encryption');
	}

    public function setup_payment_gate($method_id, $party_id, $building_id, $value, $type = 'POS') {
        $this->x_building_id = $building_id;
        $this->x_value = $value;
        
        $method = $this->Common_Code_model->find($method_id);
        if ($method) {
            if ($method->note == 'BANK') {
                $this->bank_party($party_id);
            } else if ($method->note == 'PAYMENT_GATE') {
                $this->payment_gate_party($party_id, $method, $type);
            }
        } else {
            echo json_encode(null);
        }
    }

    private function set_config($payment_gate) {
        $query = $this->db->
        query("SELECT
                C.name AS payment_gate, B.name AS key_type, A.[key] AS key_value
            FROM
                tnc_inven_m_payment_gate_has_key AS A
            INNER JOIN
                tnc_inven_m_common_code AS B ON A.key_id = B.id
            INNER JOIN
                tnc_inven_m_common_code AS C ON A.payment_gate_id = C.id
            WHERE B.is_active =	1 AND A.is_active = 1 AND A.payment_gate_id = $payment_gate
        ");

        return $query->result();
    }

    private function bank_party($party_id) {
        $result = $this->Bank_model->find($party_id);
        echo json_encode($result);
    }

    private function payment_gate_party($party_id, $method, $type) {
        $result = $this->Common_Code_model->find($party_id);
        if ($method->name == "QRIS" && $result->name == "MIDTRANS") {
            $this->payment_gate_qris_midtrans($result, $type);
        } else {
            echo json_encode(null);
        }
    }

    private function get_key($config, $key_type) {
        foreach ($config as $key) {
            if ($key->key_type == $key_type) {
                return $this->encryption->decrypt($key->key_value);
            }
        }
        return null;
    }
















    private function payment_gate_qris_midtrans($result, $type) {
        $config = $this->set_config($result->id);
        // manual **************
        // $merchant_id = 'G921336256'; //$this->get_key($config, 'Merchant ID');
        // $client_key = 'Mid-client-HG8N55xOiDbCb3bY'; //$this->get_key($config, 'Client Key');
        // $server_key = 'Mid-server-xisFsFzh-6TiHqjautqWlPnr'; //$this->get_key($config, 'Server Key');
        // $api_url = 'https://api.sandbox.midtrans.com/v2/charge'; //$this->get_key($config, 'API URL');

        // $merchant_id = $this->get_key($config, 'Merchant ID');
        // $client_key = $this->get_key($config, 'Client Key');
        $server_key = $this->get_key($config, 'Server Key');
        $api_url = $this->get_key($config, 'API URL');

        $building = $this->Building_model->find($this->x_building_id);
        $x_payload = $this->Payload_Acquirer_model->get_by_id($result->id);
        $x_payload->acquirer = $this->Common_Code_model->find($x_payload->payload_id);
        
        $order_id = 'QRIS-' . $type . '-' . $building->code . '-' . time();
        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $this->x_value
            ],
            'customer_details' => [
                'first_name' => $building ? $building->name : 'tester',
                'email' => 'test@example.com',
                'phone' => '081-xxx-xxx-xxx'
            ],
            'qris' => [
                // manual **************
                // 'acquirer' => 'gopay', //$x_payload ? ($x_payload->acquirer ? $x_payload->acquirer->name : 'gopay') : 'gopay',
                
                'acquirer' => $x_payload ? ($x_payload->acquirer ? $x_payload->acquirer->name : 'gopay') : 'gopay',
            ]
        ];

        $url = $api_url;
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($server_key . ':')
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($http_code != 201 && $http_code != 200) {
            echo json_encode([
                'msg' => 'failed',
                'qris' => $result,
                'qris_code' => null
            ]);
        } else {
            $qris_code = $result['actions'][0]['url'] ?? null;
            echo json_encode([
                'msg' => 'success',
                'qris' => $result,
                'qris_code' => $qris_code
            ]);
        }
    }

    public function payment_gate_qris_midtrans_status($order_id, $party_id) {
        $result = $this->Common_Code_model->find($party_id);
        $config = $this->set_config($result->id);
        // $merchant_id = $this->get_key($config, 'Merchant ID');
        // $client_key = $this->get_key($config, 'Client Key');
        $server_key = $this->get_key($config, 'Server Key');
        // $api_url = $this->get_key($config, 'API URL');

        // manual **************
        // $merchant_id = 'G921336256';
        // $client_key = 'Mid-client-HG8N55xOiDbCb3bY';
        // $server_key = 'Mid-server-xisFsFzh-6TiHqjautqWlPnr';
        // $api_url = 'https://api.sandbox.midtrans.com/v2/charge';

        $url = "https://api.sandbox.midtrans.com/v2/$order_id/status";
        $headers = [
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($server_key . ':')
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
}