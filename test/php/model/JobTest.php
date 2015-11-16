<?php

require_once 'test/settings.php';

class JobTest extends BaseCrud {

	public function __construct() {
		parent::__construct('Job');
	}

	protected function updateObject($object) {
		$object->name = 'test';
		$object->cmd = 'echo Hello World';
	}

	protected function createObject() {
		return Fixtures::getJob();
	}
}