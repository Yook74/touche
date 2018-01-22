<?php
include_once("../lib/header.php");
include_once("../lib/database.inc");
include_once("../lib/token.php");
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$response = '';
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $request = file_get_contents('php://input');
    $request = json_decode($request, true);
    $query = mysqli_query($link, "SELECT * FROM CONTEST_CONFIG");
    $row = mysqli_fetch_assoc($query);
    if ($request['username'] === $row['JUDGE_USER'] && $request['password'] === $row['JUDGE_PASS']) {
        $token = EncodeToken(array('role' => 'judge'));
        $response = $token;
    }
    else {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
}
echo json_encode($response);
?>