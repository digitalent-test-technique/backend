<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/Database.php';
include_once '../../modules/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$result = $user->getUser();
$num = $result->rowCount();

if ($num > 0) {
  $user_arr = array();
  $user_arr['data'] = array();
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $user_arr['data'] = array(
      'id'          => $id,
      'first_name'  => $first_name,
      'last_name'   => $last_name,
      'email'       => $email,
      'address'     => $address,
      'phone'       => $phone,
    );
  }
  http_response_code(200);
  echo json_encode($user_arr);
} else {
  http_response_code(400);
  echo json_encode(array(
    "message" => "Aucun utilisateur trouvé."
  ));
}
