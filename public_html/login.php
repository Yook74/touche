<?php
include_once("lib/header.php");
include_once("lib/database.inc");
include_once("lib/token.php");
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$response = '';
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $request = file_get_contents('php://input');
    $request = json_decode($request, true);
    $stmt = mysqli_prepare($link, "SELECT TEAM_ID, PASSWORD FROM TEAMS WHERE USERNAME = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    $username = $request['username'];
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $teamId, $teamPassword);
    while (mysqli_stmt_fetch($stmt)) {
        if ($teamPassword === $request['password']) {
            $token = EncodeToken(array('role' => 'user', 'id' => $teamId));
            mysqli_stmt_close($stmt);
            $response = $token;
            break;
        }
        else {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }
}
echo json_encode($response);
?>