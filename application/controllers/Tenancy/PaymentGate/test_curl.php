<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test_curl extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $ch = curl_init("https://api.sandbox.midtrans.com/v2/charge");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $err = curl_error($ch);
        var_dump($err);
    }
}
