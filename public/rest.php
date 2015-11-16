<?php
require_once 'settings.php';

class Rest {
  
  public function run() {
    $dic = DiContainer::instance();
    try {
      header('Content-type: application/json');
      $dic->log->debug(__CLASS__, "request: ".var_export($_REQUEST, true));

      $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
      $allowedMethods = array("get", "delete", "post");
      
      if (!in_array($requestMethod, $allowedMethods)) {
        throw ErrorException("Unsupported request method $requestMethod");
      }
      if (array_key_exists('_', $_REQUEST)) {
        unset($_REQUEST['_']);
      }
      $restCtrl = new RestCtrl($_SERVER['REQUEST_URI'], $_REQUEST);
      $result = $restCtrl->$requestMethod();
      echo ($this->jsonEncode($result));
    }
    catch (ValidationException $e) {
      return json_encode(array("error" => $e->errors()));
    }
    catch (ApplicationException $e) {
      return json_encode(array("error" => $e->getMessage()));
    }
    catch(RuntimeException $e) {
      $dic->log->error(__CLASS__, $e->getMessage());
      header($_SERVER['SERVER_PROTOCOL']. " 500 ".$e->getMessage());
    }
    catch(ErrorException $e) {
      $dic->log->error(__CLASS__, $e->getMessage());
      $dic->log->error(__CLASS__, $e->getTraceAsString());
      header($_SERVER['SERVER_PROTOCOL']. " 500 ".$e->getMessage());
    }
  }
  
  private function authorize() {
  }
  
  private function jsonEncode($item) {
    $result = "";
    if (is_array($item)) {
      $result .= "[";
      foreach($item as $tmp) {
        if (strlen($result) > 1) {
          $result .= ",";
        }
        $result .= $this->jsonEncode($tmp);
      }
      $result .= "]";
    }
    else {
      if ($item instanceof DbObject || $item instanceof DataWrapper) {
        return $item->jsonEncode();
      }
      else if ($item instanceof stdclass) {
        return json_encode($item);
      }
    }
    return $result;
  }
}

$rest = new Rest();
echo $rest->run();
