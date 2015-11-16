<?php

class RestCtrl {
  private $uri;
  private $arg;
  private $cls;
  private $uid;
  private $method;
  
  /**
   * The uri can have the following forms:
   * 
   * /rest/class/uid                            returns a object by the given object or delete it
   * 
   * /rest/class/uid?method=method_name         which returns the result of calling the method
   *                                            on the object specified by the uid
   *                                            
   * /rest/class?method=method_name             which returns the result of calling the static method
   *                                            on the class
   *                                            
   * /rest/class?name=value...                  which return an array of objects which all qualifies with
   *                                            the name-value pair set
   *                                            
   * /rest/class                                will return object of the given cls or create or update it
   */
  public function __construct($uri, array $arg = array()) {
    set_error_handler(array(__CLASS__, 'throwError'), E_ALL | E_STRICT);
    
    $this->uri = $uri;
    $this->arg = $arg;
    $this->parseUri($uri);
  }
  
  public static function throwError($errno, $errstr, $errfile = "", $errline = 0, $errcontext = null) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
  }
  
  public function get() {
    $result = null;
    $clazz = $this->cls;
    
    if ($this->uid) {
      $result = $clazz::getByUid($this->uid);
      if (!$result) {
        throw new ErrorException("http.get invalid uri $this->uri");
      }
      if (count($this->arg) > 0) {
        if (array_key_exists("method", $this->arg)) {
          $result = $this->callMethod($result);
        }
      }
    }
    else {
    	$i = 0;
    	foreach(array('orderby', 'order', 'groupby', 'limit') as $x) {
    		if(array_key_exists($x, $this->arg)) {
    			$i++;
    		}
    	}
    	
    	$arguments = '';
    	$arguments .= (array_key_exists('order', $this->arg) && array_key_exists('orderby', $this->arg) ? ' '.$this->arg['orderby'].' '.strtoupper($this->arg['order']) : '');
    	$arguments .= (array_key_exists('groupby', $this->arg) ? ' GROUP BY '.$this->arg['groupby'] : '');
    	$arguments .= (array_key_exists('limit', $this->arg) ? ' LIMIT '.$this->arg['limit'] : '');
    	
    	foreach(array('orderby', 'order', 'groupby', 'limit') as $x) {
    		if(array_key_exists($x, $this->arg)) {
    			unset($this->arg[$x]);
    		}
    	}
    	
      if (count($this->arg) > 0) {
        if (array_key_exists("method", $this->arg)) {
          $result = $this->callStatic();
        }
        else {
            $result = $clazz::getBy($this->arg, empty($arguments) ? array() : array($arguments));
        }
      }
      else {
        $result = $clazz::getAll(empty($arguments) ? array() : array($arguments));
      }
    }
    return $result;
  }
  
  public function delete() {
    if (!($this->cls || $this->uid)) {
      throw new ErrorException("http.delete invalid uri $this->uri");
    }
    $clazz = $this->cls;
    $object = $clazz::getByUid($this->uid);
    $object->destroy();
  }
  
  public function post() {
    if (!$this->cls && !count($this->arg)) {
      throw new ErrorException("http.post invalid uri $this->uri");
    }
    $clazz = $this->cls;
    $object = null;

    if (array_key_exists("method", $this->arg)) {
      $object = new $clazz();
      if($this->uid > 0) {
        $object = $object::getByUid($this->uid);
      }
      $object = $this->callMethod($object);
      return $object;
    }
    else {
      if (!empty($this->arg["uid"])) {
        $object = $clazz::getByUid($this->arg["uid"]);
        $object->setData($this->arg);
      }
      else {
        $object = new $clazz();
        $object->setData($this->arg); // This will trigger changes
      }
      $object->save();
      return new DataWrapper(array("uid" => $object->uid));
    }
  }
  
  private function parseUri() {
		DiContainer::instance()->log->debug(__CLASS__, "uri: $this->uri");
    if (empty($this->uri)) {
      throw new ErrorException("Invalid uri $this->uri");
    }
    
    $uri = preg_split("/\?/", $this->uri); // split argument from uri
    $tmp = preg_split("/\//", $uri[0]);    // split the uri into parts

    if (count($tmp) < 2 || count($tmp) > 4) {
      throw new ErrorException("Invalid uri $this->uri");
    }
    
    $this->cls = $tmp[2];
    
    if (count($tmp) > 3) {
      $this->uid = $tmp[3];
    }
  }
  
  private function callStatic() {
    $name = $this->arg["method"];
    unset($this->arg["method"]);
    $inspect = new ReflectionClass($this->cls);
    $method = $inspect->getMethod($name);
    return $method->invokeArgs(null, $this->arg);
  }

  private function callMethod($object) {
    $name = $this->arg["method"];
    unset($this->arg["method"]);
    $cls = get_class($object);
    $inspect = new ReflectionClass($cls);
    $method = $inspect->getMethod($name);
    return $method->invokeArgs($object, $this->arg);
  }
}

?>