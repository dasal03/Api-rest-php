<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './class/employees.php';
include_once './class/validation.php';

try {
  $database = new Database();
  $db = $database->getConnection();
  $item = new Employee($db);
  $data = json_decode(file_get_contents("php://input"));

  $item->name = $data->name;
  $item->email = $data->email;
  $item->age = $data->age;
  $item->designation = $data->designation;

  $validation = new Validation();
  $campos = $validation->validate($data);

  if (count($campos) > 0) {
    $status_code = 400;
    $response = [
      'status' => $status_code,
      'message' => array_pop($campos),
      'data' => []
    ];
  } else {
    if ($item->createEmployee()) {
      $status_code = 200;
      $response = [
        'status' => $status_code,
        'message' => 'Employee created successfully',
        'data' => []
      ];
    } else {
      $status_code = 404;
      $response = [
        'status' => $status_code,
        'message' => 'Employee cannot be created',
        'data' => []
      ];
    }
  }
  echo json_encode($response);
} catch (Exception $e) {
  echo $e->getMessage();
}
