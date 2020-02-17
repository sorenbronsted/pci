<?php
namespace sbronsted;

require_once 'test/settings.php';

class JobResultTest extends BaseCrud {

	public function __construct() {
		parent::__construct(JobResult::class);
	}

	protected function updateObject($object) {
		$object->start = '2015-10-19 13:45:10';
		$object->stop = '2015-10-19 13:55:45';
		$object->log = 'More log';
		$object->jobstate_uid = JobState::RUNNING;
		$object->job_uid = 2;
	}

	protected function createObject() : DbObject {
		return Fixtures::getJobResult();
	}
}