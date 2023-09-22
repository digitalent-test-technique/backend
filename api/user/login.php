<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';
include_once '../../modules/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$data = json_decode(file_get_contents("php://input"));

$user->email = $data->email;
$email_exists = $user->isEmailExists();

include_once '../../config/core.php';
include_once '../../libs/php-jwt/src/BeforeValidException.php';
include_once '../../libs/php-jwt/src/ExpiredException.php';
include_once '../../libs/php-jwt/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

if ($email_exists && password_verify($data->password, $user->password)) {
  $token = array(
    "iat" => $issued_at,
    "exp" => $expiration_time,
    "iss" => $issuer,
    "data" => array(
      "id" => $user->id
    )
  );

  $jwt = JWT::encode($token, $key);
  http_response_code(200);
  echo json_encode(array(
    "message" => "Connexion réussie.",
    "token" => $jwt,
    "id" => $user->id
  ));
} else {
  http_response_code(401);
  echo json_encode(array(
    "success" => false,
    "message" => "Échec de la connexion. Votre adresse e-mail ou votre mot de passe est incorrect."
  ));
}
