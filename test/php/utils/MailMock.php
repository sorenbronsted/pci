<?php
namespace sbronsted;

use PHPMailer\PHPMailer\PHPMailer;

class MailMock extends PHPMailer {
	public $isSent = false;

	public function __construct($exceptions) {
		parent::__construct($exceptions);
	}

	public function send() {
		$this->exceptions = true;
		if(!empty($this->ErrorInfo)) {
			return false;
		}
		$this->isSent = true;
		return true;
	}
}