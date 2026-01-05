<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans_Qris2_test extends CI_Controller {

    private $server_key = 'Mid-server-xisFsFzh-6TiHqjautqWlPnr'; // Ganti dengan server key sandbox kamu
    private $is_production = false;

    public function index()
    {
        // $this->load->view('Tenancy/PaymentGate/midtrans_qris');
        $this->create_transaction();
    }

    public function create_transaction()
    {
        // // mode select method snap
        // $order_id = 'order-' . time();
        // $gross_amount = 10000;

        // $transaction_data = [
        //     'transaction_details' => [
        //         'order_id' => $order_id,
        //         'gross_amount' => $gross_amount,
        //     ],
        //     'customer_details' => [
        //         'first_name' => 'Tester',
        //         'email' => 'test@example.com',
        //         'phone' => '081234567890',
        //     ],
        //     'item_details' => [
        //         [
        //             'id' => 'item01',
        //             'price' => 10000,
        //             'quantity' => 1,
        //             'name' => 'Produk Testing'
        //         ]
        //     ]
        // ];

        // $snap_url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';
        // $headers = [
        //     'Content-Type: application/json',
        //     'Accept: application/json',
        //     'Authorization: Basic ' . base64_encode($this->server_key . ':')
        // ];

        // $ch = curl_init();
        // curl_setopt_array($ch, [
        //     CURLOPT_URL => $snap_url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_HTTPHEADER => $headers,
        //     CURLOPT_POST => true,
        //     CURLOPT_POSTFIELDS => json_encode($transaction_data)
        // ]);

        // $response = curl_exec($ch);
        // curl_close($ch);

        // $result = json_decode($response, true);

        // $this->load->view('Tenancy/PaymentGate/midtrans_qris', ['snap' => $result]);



        //direct qris charge method
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
                'acquirer' => 'gopay' // default QRIS acquirer
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

        // QR String dari Midtrans
        $qris_code = $result['actions'][0]['url'] ?? null;
        $this->load->view('Tenancy/PaymentGate/midtrans_qris', [
            'qris' => $result,
            'qris_code' => $qris_code
        ]);
    }

    // Untuk auto-refresh status pembayaran
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

    public function callback()
    {
        // // mode select method snap
        // $raw = file_get_contents('php://input');
        // $data = json_decode($raw, true);

        // log_message('info', 'Midtrans callback: ' . print_r($data, true));

        // echo json_encode(['status' => 'ok']);


        // direct qris charge method
        $data = json_decode(file_get_contents('php://input'), true);
        log_message('info', 'Callback Midtrans: ' . print_r($data, true));
        echo json_encode(['status' => 'ok']);
    }

    public function simulate_payment($order_id)
    {
        // Simulate payment hanya berfungsi di SANDBOX
        $url = "https://api.sandbox.midtrans.com/v2/" . $order_id . "/status";

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->server_key . ':')
        ];

        $payload = [
            'transaction_status' => 'settlement' // status yang akan disimulasikan
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        echo "<pre>HTTP Code: $http_code\n";
        print_r($result);
        echo "</pre>";
    }

    public function simulate_callback($order_id)
{
    // Data simulasi callback sukses
    $callback_data = [
        "transaction_status" => "settlement",
        "payment_type" => "qris",
        "order_id" => $order_id,
        "gross_amount" => "10000.00",
        "fraud_status" => "accept",
        "status_message" => "mock payment success"
    ];

    // Kirim ke callback handler kamu sendiri
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

}
