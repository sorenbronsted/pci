<?php
namespace sbronsted;

use RuntimeException;
use stdClass;

class Project extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'name' => Property::STRING,
		'description' => Property::STRING,
		'dir' => Property::STRING,
	);

	private static $mandatories = array('name');

	public function getLatestJobResults() {
		$sql = "select jr.* from jobresult jr join buildidgenerator b on b.build_id = jr.build_id ".
					 "join project p on p.uid = b.project_uid where p.uid = ? order by jr.start desc";
		return JobResult::getObjects($sql, array('project_uid' => $this->uid));
	}

	public function drawBuildId() {
		return BuildIdGenerator::draw($this->uid);
	}

	public static function build($projectName, $user) {
		$project = Project::getOneBy(array('name' => $projectName));
		$buildId = $project->drawBuildId();
		$jobs = Job::getBy(['project_uid' => $project->uid], ['sequence']);
		foreach ($jobs as $job) {
			$result = $job->run($buildId, $user, $project->dir);
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
		$cmd = "nohup php $dir/build.php '".$this->name."' $user > /dev/null 2>&1 &";
		DiContainer::instance()->log->debug(__CLASS__,"$cmd");
		shell_exec($cmd);
	}

	public function destroy() : void {
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
		$result->isBuilding = count(JobResult::getBy(['jobstate_uid' => JobState::RUNNING])) >= 1;
		return $result;
	}

	public function jsonEncode(array $data) : array {
		$sql = "select min(jr.start) start, max(jr.stop) stop, max(jobstate_uid) jobstate_uid ".
					"from project p ".
					"join job j on p.uid = j.project_uid ".
					"join jobresult jr on j.uid = jr.job_uid ".
					"join buildidgenerator b on b.project_uid = p.uid and jr.build_id = b.build_id ".
					"where p.uid = ? ";

		$cursor = Db::prepareQuery(self::$db, $sql, array($this->uid));
		if (!$cursor->hasNext()) {
			return $data;
		}
		$row = $cursor->next();
		$row['jobstate_uid'] = intval($row['jobstate_uid']);
		$data = array_merge($data, $row);

		if (isset($row['stop']) && $row['jobstate_uid'] != JobState::RUNNING) {
			$start = Timestamp::parse($row['start']);
			$stop = Timestamp::parse($row['stop']);
			$seconds = $stop->diff($start);
			$h = str_pad(floor($seconds/3600),2, '0',STR_PAD_LEFT);
			$m = str_pad(floor(($seconds - ($h*3600))/60), 2, '0', STR_PAD_LEFT);
			$s = str_pad($seconds - ($h*3600) - ($m*60), 2, '0', STR_PAD_LEFT);
			$data['duration'] = "1970-01-01 $h:$m:$s";
		}
		return $data;
	}


	public function getMandatories() : array {
		return self::$mandatories;
	}

	protected function getProperties() : array {
		return self::$properties;
	}
}
