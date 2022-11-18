<?php

class Employee
{
  private $conn;
  private $db_table = "Employee";
  public $id;
  public $name;
  public $email;
  public $age;
  public $designation;
  public $created;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function createEmployee()
  {
    $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        name = :name, 
                        email = :email, 
                        age = :age, 
                        designation = :designation";

    $stmt = $this->conn->prepare($sqlQuery);

    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->age = htmlspecialchars(strip_tags($this->age));
    $this->designation = htmlspecialchars(strip_tags($this->designation));

    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":age", $this->age);
    $stmt->bindParam(":designation", $this->designation);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  public function getEmployees()
  {
    $sqlQuery = "SELECT 
                        id, 
                        name, 
                        email, 
                        age, 
                        designation, 
                        created 
                      FROM 
                        " . $this->db_table . "";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    $itemCount = $stmt->rowCount();
    // echo json_encode($itemCount);
    if ($itemCount > 0) {
      return $stmt;
    } else {
      throw new Exception("No employees");
    }
  }


  public function getSingleEmployee()
  {
    $sqlQuery = "SELECT
                        id, 
                        name, 
                        email, 
                        age, 
                        designation, 
                        created
                      FROM
                        " . $this->db_table . "
                    WHERE 
                       id = ?
                    LIMIT 0,1";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->name = $dataRow['name'];
    $this->email = $dataRow['email'];
    $this->age = $dataRow['age'];
    $this->designation = $dataRow['designation'];
    $this->created = $dataRow['created'];


    if ($dataRow != null) {
      return True;
    } else {
      throw new Exception('Employee does not exist in the database, please check the id');
    }
  }

  public function validateEmployee()
  {
    $sqlQuery = "SELECT
                        id, 
                        name, 
                        email, 
                        age, 
                        designation, 
                        created
                      FROM
                        " . $this->db_table . "
                    WHERE 
                       id = ?
                    LIMIT 0,1";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dataRow != null) {
      return True;
    } else {
      throw new Exception('Employee does not exist in database');
    }
  }

  public function updateEmployee()
  {
    $getById = $this->validateEmployee();

    if ($getById) {
      $sqlQuery = "UPDATE
                          " . $this->db_table . "
                      SET
                          name = :name, 
                          email = :email, 
                          age = :age, 
                          designation = :designation, 
                          created = :created
                      WHERE 
                          id = :id";

      $stmt = $this->conn->prepare($sqlQuery);

      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->email = htmlspecialchars(strip_tags($this->email));
      $this->age = htmlspecialchars(strip_tags($this->age));
      $this->designation = htmlspecialchars(strip_tags($this->designation));
      $this->created = htmlspecialchars(strip_tags($this->created));
      $this->id = htmlspecialchars(strip_tags($this->id));

      $stmt->bindParam(":name", $this->name);
      $stmt->bindParam(":email", $this->email);
      $stmt->bindParam(":age", $this->age);
      $stmt->bindParam(":designation", $this->designation);
      $stmt->bindParam(":created", $this->created);
      $stmt->bindParam(":id", $this->id);

      if ($stmt->execute()) {
        return true;
      }
      return false;
    } else {
      throw new Exception('Employee does not exist in database');
    }
  }

  function deleteEmployee()
  {
    $getById = $this->validateEmployee();

    if ($getById) {
      $sqlQuery = "DELETE FROM 
                  " . $this->db_table .
        " WHERE id = ?";
      $stmt = $this->conn->prepare($sqlQuery);

      $this->id = htmlspecialchars(strip_tags($this->id));

      $stmt->bindParam(1, $this->id);

      if ($stmt->execute()) {
        return true;
      }
      return false;
    } else {
      throw new Exception('Employee does not exist in database');
    }
  }
}
