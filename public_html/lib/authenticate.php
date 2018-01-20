<?php
include_once("token.php");
$headers = getallheaders();
$userRole = "";
if(isset($headers['Authorization'])) {
    $tokenData = DecodeToken($headers['Authorization']);
}
?>