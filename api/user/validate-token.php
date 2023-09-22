<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


include_once '../../config/core.php';
include_once '../../libs/php-jwt/src/BeforeValidException.php';
include_once '../../libs/php-jwt/src/ExpiredException.php';
include_once '../../libs/php-jwt/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"));

$token = isset($data->token) ? $data->token : "";

if ($token) {

  try {
    $decoded = JWT::decode($token, $key, array('HS256'));
    http_response_code(200);
    echo json_encode(array(
      "message" => "Accès accordé.",
      "data" => $decoded->data
    ));
  } catch (Exception $ex) {

    http_response_code(401);
    echo json_encode(array(
      "message" => "Accès refusé.",
      "error" => $ex->getMessage()
    ));
  }
} else {
  http_response_code(400);
  echo json_encode(array(
    "message" => "Quelque chose s'est mal passé, veuillez réessayer."
  ));
}
