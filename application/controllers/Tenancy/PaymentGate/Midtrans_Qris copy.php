<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans_Qris extends CI_Controller {

    // update 'sandbox'or 'production'
    private $mode = 'sandbox';

    private $server_key;

    public function __construct()
    {
        parent::__construct();

        if ($this->mode === 'production') {
            $this->server_key = 'Mid-server-1KIPzToZhEAJ7JRZAIrmSMS3';
        } else {
            $this->server_key = 'Mid-server-xisFsFzh-6TiHqjautqWlPnr';
        }
    }

    public function index()
    {
        $this->create_transaction();
    }

    public function create_transaction()
    {
        $order_id = 'qris-' . time();

        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => 1000
            ],
            'customer_details' => [
                'first_name' => 'Tester QRIS',
                'email' => 'tester@example.com',
                'phone' => '081234567890'
            ],
            'qris' => [
                'acquirer' => 'gopay'
            ]
        ];

        $url = $this->mode === 'production' 
            ? 'https://api.midtrans.com/v2/charge' 
            : 'https://api.sandbox.midtrans.com/v2/charge';

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->server_key . ':')
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

        if ($http_code != 200 && $http_code != 201) {
            echo "<pre>❌ Gagal membuat QRIS:\n";
            print_r($result);
            echo "</pre>";
            return;
        }

        $qris_code = $result['actions'][0]['url'] ?? null;

        $this->load->view('Tenancy/PaymentGate/midtrans_qris', [
            'qris' => $result,
            'qris_code' => $qris_code,
            'mode' => $this->mode,
            'http_code' => $http_code,
        ]);
    }

    public function check_status($order_id)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        $url = $this->mode === 'production'
            ? "https://api.midtrans.com/v2/$order_id/status"
            : "https://api.sandbox.midtrans.com/v2/$order_id/status";

        $headers = [
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->server_key . ':')
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

    public function callback()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        log_message('info', 'Callback Midtrans (' . $this->mode . '): ' . print_r($data, true));
        echo json_encode(['status' => 'ok']);
    }

    private function get_transactions()
    {
        $url = $this->mode === 'production'
            ? 'https://api.midtrans.com/v2/transactions'
            : 'https://api.sandbox.midtrans.com/v2/transactions';

        $headers = [
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->server_key . ':')
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != 200) {
            echo "<pre>❌ Gagal mengambil list transaction:\n";
            print_r(json_decode($response, true));
            echo "</pre>";
            return [];
        }

        $result = json_decode($response, true);
        return $result['transactions'] ?? [];
    }
}
