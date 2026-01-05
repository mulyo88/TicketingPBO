<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testcom extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load common helpers/libraries you will need
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);

        // Example: load a model at application/models/Tenancy/Ticketing/Ticket_model.php
        // If you don't have this model, either create it or remove the line below.
        // $this->load->model('Tenancy/Ticketing/Ticket_model', 'ticket_model', TRUE);
    }

    /**
     * GET /Tenancy/Ticketing/Testcom
     * List tickets (basic)
     */
    public function index()
    {
       $this->load->view('Tenancy/Ticketing/Trans/test_com');
    }


    public function send_plc($tcp = "192.168.8.100:5000")
    {
        $parsed = parse_url($tcp);
        $ip   = $parsed['host'];
        $port = $parsed['port'];

        header('Content-Type: application/json');
        // $ip   = "192.168.8.100";   // Hercules di PC lokal
        // $ip   = "127.0.0.1"; // Loopback untuk testing di PC lokal
        // $port = 5000;

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            echo json_encode([
                'step' => 'SOCKET_CREATE_FAIL',
                'msg'  => socket_strerror(socket_last_error())
            ]);
            return;
        }

        if (!socket_connect($socket, $ip, $port)) {
            echo json_encode([
                'step' => 'SOCKET_CONNECT_FAIL',
                'msg'  => socket_strerror(socket_last_error())
            ]);
            return;
        }

        // socket_write($socket, "*OPEN\r\n", 6);
        socket_write($socket, "*OPEN13# \r\n", 10);
        socket_close($socket);

        echo json_encode([
            'step' => 'SOCKET_OK'
        ]);
    }
}