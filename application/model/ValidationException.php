<?php

class ValidationException extends ApplicationException {
  private $errors;

  public function __construct($errors = array()) {
    parent::__construct("validation exception");
    $this->errors = $errors;
  }
  
  public function __set($name, $message) {
    $this->errors[$name] = $message;
  }
  
  public function __get($name) {
    return (array_key_exists($name, $this->errors) ? $this->errors[$name] : null);
  }
  
  public function addAll(array $newErrors) {
    $this->errors = array_merge($this->errors, $newErrors);
  }

  public function errors() {
    return array(__CLASS__ => $this->errors);
  }
  
  public function toString() {
    $msg = "";
    foreach($this->errors as $name => $message) {
      if ($msg) {
        $msg .= ",";
      }
      $msg .= "$name : $message";
    }
    return "Validation exception, ".$msg;
  }
  
  public function __toString() {
    return $this->toString();
  }
}