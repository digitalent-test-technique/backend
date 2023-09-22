<?php

/**
 * use `*` for now
 */
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

$user->first_name = $data->first_name;
$user->last_name = $data->last_name;
$user->email = $data->email;
$user->address = $data->address;
$user->password = $data->password;
$user->phone = $data->phone;
if (
  !empty($user->first_name) &&
  !empty($user->last_name) &&
  !empty($user->address) &&
  !empty($user->password) &&
  !empty($user->phone) &&
  !empty($user->email)
) {
  if ($user->isEmailExists()) {
    http_response_code(400);
    echo json_encode(array(
      "message" => "The email already exist, please try with another email"
    ));
  } else if (
    $user->create()
  ) {
    http_response_code(200);
    echo json_encode(array(
      "message" => "User Created Successfully"
    ));
  } else {
    http_response_code(400);
    echo json_encode(array(
      "message" => "Unable to create user."
    ));
  }
}
