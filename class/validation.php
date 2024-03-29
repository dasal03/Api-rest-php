<?php

class Validation
{
  /**
   * Funcion encargada de validar datos
   * @param mixed $data
   * @return array $campos
   */
  function validate($data)
  {
    $campos = [];

    if (isset($data)) {
      $name = isset($data->name) ? $data->name : '';
      $email = isset($data->email) ? $data->email : '';
      $age = isset($data->age) ? $data->age : '';
      $designation = isset($data->designation) ? $data->designation : '';

      if ($name == "") {
        array_push($campos, "Name is required");
      } else {
        if (!is_string($name)) {
          array_push($campos, "Name must be a string");
        }
      }

      if ($email == "") {
        array_push($campos, "Email is required");
      } else {
        if (!is_string($email) || strpos($email, "@") === false) {
          array_push($campos, "Insert valid email address");
        }
      }

      if ($age == "") {
        array_push($campos, "Age is required");
      } else {
        if (!is_int($age)) {
          array_push($campos, "Age must be an integer");
        }
      }

      if ($designation == "") {
        array_push($campos, "Designation is required");
      } else {
        if (!is_string($designation)) {
          array_push($campos, "Designation must be a string");
        }
      }
    }
    return $campos;
  }
}
