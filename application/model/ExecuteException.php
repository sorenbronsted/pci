<?php
namespace sbronsted;

use Exception;
use Throwable;

class ExecuteException extends Exception {
	public function __construct($message = "", $code = 0, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}