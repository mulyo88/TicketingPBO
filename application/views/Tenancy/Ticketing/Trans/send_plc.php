<?php
$ip   = "192.168.10.88";
$port = 8868;

$data = json_decode(file_get_contents("php://input"), true);
$cmd  = $data['cmd'];

$json = json_encode([
    "cmd" => $cmd
]);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec"=>3,"usec"=>0]);

if (!socket_connect($socket, $ip, $port)) {
    die("Gagal konek PLC");
}

socket_write($socket, $json, strlen($json));

$response = socket_read($socket, 1024);

socket_close($socket);

echo "Perintah $cmd terkirim ke PLC";
?>