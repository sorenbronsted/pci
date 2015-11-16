<?php

class Project extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'name' => Property::STRING,
		'description' => Property::STRING,
	);

	private static $mandatories = array('name');

	public function getLatestJobResults() {
		$sql = "select jr.* from jobresult jr join buildidgenerator b on b.build_id = jr.build_id ".
					 "join project p on p.uid = b.project_uid where p.uid = ? order by jr.start desc";
		return JobResult::getObjects($sql, array('project_uid' => $this->uid));
	}

	private function drawBuildId() {
		return BuildIdGenerator::draw($this->uid);
	}

	public static function build($projectName, $user) {
		$project = Project::getOneBy(array('name' => $projectName));
		$buildId = $project->drawBuildId();
		$jobs = Job::getBy(array('project_uid' => $project->uid), array('sequence'));
		foreach ($jobs as $job) {
			$result = $job->run($buildId, $user);
			if ($result->jobstate_uid == JobState::FAILED) {
				throw new RuntimeException("Project $projectName build failed");
			}
		}
	}

	public static function buildInBackgroundByName($projectName, $user) {
		$project = Project::getOneBy(array('name' => $projectName));
		$project->buildInBackGround($user);
	}

	public function buildInBackground($user) {
		$dir = dirname(dirname(dirname(__FILE__))).'/public';
		$cmd = "nohup php $dir/build.php ".$this->name." $user > /dev/null 2>&1 &";
		DiContainer::instance()->log->debug(__CLASS__,"$cmd");
		shell_exec($cmd);
	}

	public function destroy() {
		$jobs = Job::getBy(array('project_uid' => $this->uid));
		foreach($jobs as $job) {
			$job->destroy();
		}
		try {
			$big = BuildIdGenerator::getOneBy(array('project_uid' => $this->uid));
			$big->destroy();
		}
		catch(NotFoundException $e) {
			// Do nothing
		}
		parent::destroy();
	}

	public static function isBuilding() {
		$result = new stdClass();
		$result->isBuilding = count(JobResult::getBy(array('jobstate_uid' => JobState::RUNNING))) >= 1;
		return $result;
	}

	protected function onJsonEncode($data) {
		$sql = 	"select t.build_id, min(jr.uid) min_uid, max(jr.uid) max_uid ".
						"from (select p.uid, max(jr.build_id) build_id ".
						"	from project p ".
						"	join job j on p.uid = j.project_uid ".
						"	join jobresult jr on j.uid = jr.job_uid ".
						"	where p.uid = ? ".
						"	group by p.uid ".
						") t ".
						"join jobresult jr on t.build_id = jr.build_id ".
						"group by build_id";
		$cursor = Db::prepareQuery(self::$db, $sql, array($this->uid));
		if (!$cursor->hasNext()) {
			return $data;
		}
		$row = $cursor->next();
		$jrMin = JobResult::getByUid($row['min_uid']);
		$jrMax = JobResult::getByUid($row['max_uid']);

		$data['start'] = $jrMin->start->toString();

		if ($jrMax->stop != null) {
			$data['stop'] = $jrMax->stop->toString();
			$data['jobstate_uid'] = $jrMax->jobstate_uid;
			$seconds = $jrMax->stop->diff($jrMin->start);
			$h = floor($seconds/3600);
			$m = floor($seconds/60);
			$s = $seconds - ($h*3600) - ($m*60);
			$data['duration'] = "$h:$m:$s";
		}
		return $data;
	}


	public function getMandatories() {
		return self::$mandatories;
	}

	protected function getProperties() {
		return self::$properties;
	}
}
