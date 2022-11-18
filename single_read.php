<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('./config/database.php');
include_once('./class/employees.php');

try {
  $database = new Database();
  $db = $database->getConnection();
  $item = new Employee($db);
  $item->id = isset($_GET['id']) ? $_GET['id'] : die();

  $item->getSingleEmployee();
  if ($item->name != null) {
    $data = [];
    $status_code = 200;
    $emp_arr = array(
      "id" =>  $item->id,
      "name" => $item->name,
      "email" => $item->email,
      "age" => $item->age,
      "designation" => $item->designation,
      "created" => $item->created
    );
    array_push($data, $emp_arr);

    $response = [
      'status' => $status_code,
      'message' => 'Employee',
      'data' => $data
    ];
    echo json_encode($response);
  }
} catch (Exception $e) {
  $status_code = 404;
  $response = [
    'status' => $status_code,
    'message' => $e->getMessage(),
    'data' => []
  ];
  echo json_encode($response);
}
