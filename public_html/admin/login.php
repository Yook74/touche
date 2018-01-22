<?php
include_once("../lib/header.php");
include_once("../lib/database.inc");
include_once("../lib/token.php");
include("lib/admin_config.inc");
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$response = '';
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $request = file_get_contents('php://input');
    $request = json_decode($request, true);
    if ($request['username'] === $admin_user && $request['password'] === $admin_pass) {
        $token = EncodeToken(array('role' => 'admin'));
        $response = $token;
    }
    else {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
}
echo json_encode($response);
?>