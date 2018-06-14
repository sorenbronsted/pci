<?php
namespace ufds;

class Job extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'name' => Property::STRING,
		'cmd' => Property::STRING,
		'sequence' => Property::INT,
		'project_uid' => Property::INT,
	);

	private static $mandatories = array(
		'name', 'project_uid', 'sequence'
	);

	public function getMandatories() {
		return self::$mandatories;
	}

	public function run($buildId, $user, $dir) {
		$result = new JobResult();
		$result->job_uid = $this->uid;
		$result->user = $user;
		$result->build_id = $buildId;
		$result->setState(JobState::RUNNING);

		DiContainer::instance()->log->debug(__CLASS__,"Running: ".$this->cmd);
		$result->log = '';
		if (!is_null($dir)) {
			chdir($dir);
			putenv("HOME=$dir");
		}
		$ph = popen($this->cmd." 2>&1", 'r');
		if ($ph === false) {
			$result->log = 'popen failed';
			$result->setState(JobState::FAILED);
			return $result;
		}

		while (!feof($ph)) {
			$result->log .= fread($ph, 4096);
		}
		$retval = pclose($ph);
		if ($retval != 0) {
			$result->setState(JobState::FAILED);
		}
		else {
			$result->setState(JobState::DONE);
		}
		return $result;
	}

	public function destroy() {
		$results = JobResult::getBy(array('job_uid' => $this->uid));
		foreach($results as $result) {
			$result->destroy();
		}
		parent::destroy();
	}
	protected function getProperties() {
		return self::$properties;
	}
}