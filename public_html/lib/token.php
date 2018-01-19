<?php 
require "/home/contest_skeleton/src/vendor/autoload.php";
use \Firebase\JWT\JWT;
$key = "YOUR_KEY_HERE";

function EncodeToken($payload) {
    $jwt = JWT::encode($payload, $key);
    return $jwt;
}

function DecodeToken($token) {
    $decoded = JWT::decode($token, $key, array('HS256'));
    return $decoded;
}
?>