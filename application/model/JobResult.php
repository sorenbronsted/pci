<?php
namespace sbronsted;

class JobResult extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'start' => Property::TIMESTAMP,
		'stop' => Property::TIMESTAMP,
		'log' => Property::STRING,
		'user' => Property::STRING,
		'build_id' => Property::INT,
		'jobstate_uid' => Property::INT,
		'job_uid' => Property::INT
	);

	private static $mandatories = array(
		'job_uid', 'build_id'
	);

	public function setState($state) {
		if ($this->jobstate_uid == $state) {
			return;
		}

		$this->jobstate_uid = $state;
		if ($this->jobstate_uid == JobState::RUNNING) {
			$this->start = new Timestamp();
		}
		else {
			$this->stop = new Timestamp();
		}
		$this->save();
	}

	public function getJob() {
		return Job::getByUid($this->job_uid);
	}

	public static function getByProjectUid($projectUid) {
		$sql = "select jr.* from jobresult jr join job j on j.uid = jr.job_uid ".
					 "where j.project_uid = ? order by jr.build_id desc, jr.uid desc limit 50";
		return self::getObjects($sql,array('project_uid' => $projectUid));
	}

	public function jsonEncode(array $data) : array {
		$job = Job::getByUid($this->job_uid);
		$data['jobname'] = $job->name;
		return $data;
	}


	public function getMandatories() : array {
		return self::$mandatories;
	}

	protected function getProperties() : array {
		return self::$properties;
	}
}