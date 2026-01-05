<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xendit_Qris extends CI_Controller {

    private $xendit_api_key = 'xnd_development_qhvj7BMjHYGG5imuC2QZBAuJ7OP2jT6BzCObum1XSdQ9S2fZMlrT8h4YtP6LOrF';
    private $xendit_api_url = 'https://api.xendit.co/qr_codes';

    public function index()
    {
        $this->load->view('Tenancy/PaymentGate/xendit_qris');
    }

    /**
     * Create a new QRIS payment (sandbox/test mode)
     */
    public function create_qris()
    {
        $external_id = 'order-' . time();

        $data = [
            'external_id'  => $external_id,
            'amount'       => 10000,
            'callback_url' => 'https://webhook.site/25d6af65-cff6-40d9-99ba-9a2e23a8ee54',
            'type'         => 'DYNAMIC'
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->xendit_api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => $this->xendit_api_key . ":",
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json']
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($response, true);

        echo "<h3>Response Create QRIS (HTTP $http_code)</h3><pre>";
        print_r($result);
        echo "</pre>";

        if (isset($result['id'])) {
            echo "<p><strong>✅ QRIS Created Successfully!</strong></p>";
            echo "<p>QR ID: <code>{$result['id']}</code></p>";
            echo "<p>External ID: <code>{$result['external_id']}</code></p>";
            echo "<p>Amount: Rp " . number_format($result['amount'], 0, ',', '.') . "</p>";

            // QR code image (using phpqrcode via Google Charts fallback)
            $qr_string = $result['qr_string'] ?? 'SOME_RANDOM_QR';
            echo "<h4>QR Code:</h4>";
            echo "<img src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qr_string) . "' alt='QR Code'>";

            echo "<hr><a href='" . base_url("Tenancy/PaymentGate/Xendit_Qris/simulate_payment/" . $result['id']) . "' target='_blank'>🔁 Simulate Payment</a>";
        } else {
            echo "<p style='color:red'>❌ Gagal membuat QRIS</p>";
            if (!empty($error_msg)) {
                echo "<pre>CURL Error: $error_msg</pre>";
            }
        }
    }

    /**
     * Simulate payment (only works in sandbox/test mode)
     */
    public function simulate_payment($qr_id = null)
    {
        if (!$qr_id) {
            echo "⚠️ QR ID belum diberikan. Gunakan URL seperti:<br>";
            echo "<code>" . base_url('Tenancy/PaymentGate/Xendit_Qris/simulate_payment/qr_xxx') . "</code>";
            return;
        }

        $url = "https://api.xendit.co/qr_codes/" . $qr_id . "/simulate_payment";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => $this->xendit_api_key . ":",
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json']
        ]);

        echo "<p>🔍 DEBUG Endpoint: $url</p>";
        echo "<p>🔑 Key Prefix: " . substr($this->xendit_api_key, 0, 15) . "...</p>";

        $response   = curl_exec($ch);
        $http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo "<p style='color:red'>CURL Error: " . curl_error($ch) . "</p>";
        }

        curl_close($ch);

        echo "<hr><strong>HTTP Code:</strong> $http_code<br><br>";
        echo "<pre>";
        print_r(json_decode($response, true));
        echo "</pre>";

        if ($http_code == 200) {
            echo "<p style='color:green'><strong>✅ Payment Simulated Successfully!</strong></p>";
        } elseif ($http_code == 404) {
            echo "<p style='color:red'><strong>❌ NOT FOUND:</strong> Pastikan QR ID dan API key berasal dari mode yang sama (Test Mode).</p>";
        } else {
            echo "<p style='color:orange'>⚠️ Periksa response di atas untuk detail error.</p>";
        }
    }
}
