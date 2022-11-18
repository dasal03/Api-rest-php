<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once './config/database.php';
include_once './class/employees.php';

try {
  $database = new Database();
  $db = $database->getConnection();
  $items = new Employee($db);
  $stmt = $items->getEmployees();
  $itemCount = $stmt->rowCount();
  // echo json_encode($itemCount);
  if ($itemCount > 0) {

    $employeeArr = array();
    $employeeArr["body"] = array();
    $data = [];
    $status_code = 200;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $e = array(
        "id" => $id,
        "name" => $name,
        "email" => $email,
        "age" => $age,
        "designation" => $designation,
        "created" => $created
      );
      array_push($data, $e);
      array_push($employeeArr["body"], $e);
    }
    $response = [
      'status' => $status_code,
      'message' => 'List of employees',
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
