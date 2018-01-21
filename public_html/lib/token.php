<?php 
require "/home/contest_skeleton/src/vendor/autoload.php";
use \Firebase\JWT\JWT;

function GetKey() {
    return "YOUR_KEY_HERE";
}

function EncodeToken($payload) {
    $key = GetKey();
    $jwt = JWT::encode($payload, $key);
    return $jwt;
}

function DecodeToken($token) {
    $key = GetKey();
    $decoded = JWT::decode($token, $key, array('HS256'));
    return $decoded;
}
?>