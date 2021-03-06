<?php
namespace sbronsted;

class Fixtures {

	public static function pushMasterRequest() {
		return file_get_contents(__DIR__.'/push_master.txt');
	}

	public static function getProject() : Project {
		$o = new Project();
		$o->name = 'Sletmig';
		$o->description = 'Sletmig';
		$o->dir = '/tmp';
		return $o;
	}

	public static function getJob(Project $project = null) : Job {
		$o = new Job();
		$o->name = 'Sletmig';
		$o->cmd = 'Sletmig';
		$o->sequence = 1;
		$o->project_uid = ($project == null ? 1 : $project->uid);
		return $o;
	}

	public static function getJobResult() : JobResult {
		$o = new JobResult();
		$o->start = Timestamp::parse('2015-10-19 13:37:25');
		$o->stop = Timestamp::parse('2015-10-19 13:45:11');
		$o->log = 'Log from build';
		$o->jobstate_uid = JobState::DONE;
		$o->build_id = 1;
		$o->job_uid = 1;
		return $o;
	}

}

