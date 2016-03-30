<?php

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
					 "where j.project_uid = ? order by jr.build_id desc, jr.jobstate_uid asc, jr.uid desc, jr.stop desc limit 50";
		return self::getObjects($sql,array('project_uid' => $projectUid));
	}

	protected function onJsonEncode($data) {
		$job = Job::getByUid($this->job_uid);
		$data['jobname'] = $job->name;
		return $data;
	}


	public function getMandatories() {
		return self::$mandatories;
	}

	protected function getProperties() {
		return self::$properties;
	}
}