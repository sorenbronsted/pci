<?php

require_once 'test/settings.php';

class JobResultTest extends BaseCrud {

	public function __construct() {
		parent::__construct('JobResult');
	}

	protected function updateObject($object) {
		$object->start = Timestamp::parse('2015-10-19 13:45:10');
		$object->stop = Timestamp::parse('2015-10-19 13:55:45');
		$object->log = 'More log';
		$object->jobstate_uid = JobState::RUNNING;
		$object->job_uid = 2;
	}

	protected function createObject() {
		return Fixtures::getJobResult();
	}
}