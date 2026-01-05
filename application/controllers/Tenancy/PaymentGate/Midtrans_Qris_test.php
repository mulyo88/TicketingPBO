<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans_Qris_test extends CI_Controller {

    private $server_key = 'Mid-server-xisFsFzh-6TiHqjautqWlPnr'; // Ganti dengan server key sandbox kamu
    private $is_production = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->create_transaction();
    }

    // 🔹 Buat QRIS dari API Midtrans
    public function create_transaction()
    {
        $order_id = 'qris-' . time();

        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => 10000
            ],
            'customer_details' => [
                'first_name' => 'Tester QRIS',
                'email' => 'test@example.com',
                'phone' => '081234567890'
            ],
            'qris' => [
                'acquirer' => 'gopay'
            ]
        ];

        $url = 'https://api.sandbox.midtrans.com/v2/charge';
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

        // if ($http_code != 201 && $http_code != 200) {
        //     echo "<pre>Gagal membuat QRIS:\n";
        //     print_r($result);
        //     echo "</pre>";
        //     return;
        // }

        $qris_code = $result['actions'][0]['url'] ?? null;
        $this->load->view('Tenancy/PaymentGate/midtrans_qris', [
            'qris' => $result,
            'qris_code' => $qris_code
        ]);
    }

    // 🔹 Simulasikan callback pembayaran sukses
    public function simulate_callback($order_id)
    {
        $callback_data = [
            "transaction_status" => "settlement",
            "payment_type" => "qris",
            "order_id" => $order_id,
            "gross_amount" => "10000.00",
            "fraud_status" => "accept",
            "status_message" => "mock payment success"
        ];

        $url = base_url('Tenancy/PaymentGate/callback');
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($callback_data)
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        echo "<pre>Simulated callback sent to: $url\n";
        print_r($callback_data);
        echo "\nResponse:\n$response</pre>";
    }

    // 🔹 Endpoint untuk menerima callback Midtrans
    public function callback()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        log_message('info', 'Callback Midtrans: ' . print_r($data, true));
        echo json_encode(['status' => 'ok', 'received' => $data]);
    }

    // 🔹 Cek status transaksi manual
    public function check_status($order_id)
    {
        $url = "https://api.sandbox.midtrans.com/v2/$order_id/status";
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
}
