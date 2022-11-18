<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('./config/database.php');
include_once('./class/employees.php');
include_once('./class/validation.php');


try {
  $database = new Database();
  $db = $database->getConnection();
  $item = new Employee($db);
  $data = json_decode(file_get_contents("php://input"));

  $item->id = $data->id;
  $item->name = $data->name;
  $item->email = $data->email;
  $item->age = $data->age;
  $item->designation = $data->designation;
  $item->created = date('Y-m-d H:i:s');

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
    if ($item->updateEmployee()) {
      $status_code = 200;
      $response = [
        'status' => $status_code,
        'message' => 'Employee data updated',
        'data' => array(
          "id" =>  $item->id,
          "name" => $item->name,
          "email" => $item->email,
          "age" => $item->age,
          "designation" => $item->designation,
          "created" => $item->created
        )
      ];
    }
  }
  echo json_encode($response);
} catch (Exception $e) {
  $status_code = 404;
  $response = [
    'status' => $status_code,
    'message' => $e->getMessage(),
    'data' => []
  ];
  echo json_encode($response);
}
