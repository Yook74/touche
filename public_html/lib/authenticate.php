<?php
include_once("token.php");
$headers = getallheaders();
$userRole = "";
echo json_encode($headers);
if(isset($headers['Authorization'])) {
    $tokenData = DecodeToken($headers['Authorization']);
}
?>