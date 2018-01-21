<?php
include_once("token.php");
$headers = getallheaders();
$userRole = null;
$userId = null;
if(isset($headers['Authorization'])) {
    $tokenData = DecodeToken($headers['Authorization']);
    $userRole = $tokenData['role'];
    $userId = $tokenData['id'];
}
?>