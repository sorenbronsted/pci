<?php
namespace sbronsted;

require_once 'test/settings.php';

class ProjectTest extends BaseCrud {

	public function __construct() {
		parent::__construct(Project::class);
	}

	protected function updateObject($object) {
		$object->name = 'test';
		$object->description = 'test';
	}

	protected function createObject() : DbObject {
		return Fixtures::getProject();
	}

	protected function setUp() : void {
		$tables = array('project', 'job', 'jobresult', 'buildidgenerator');
		foreach($tables as $table) {
			Db::exec(DbObject::$db, "delete from $table");
		}
	}

	public function testBuild() {
		$project = Fixtures::getProject();
		$project->save();
		$job = Fixtures::getJob($project);
		$job->cmd = 'echo "Hello World"';
		$job->save();

		Project::build($project->name, 'me');

		$states = $project->getLatestJobResults();
		$this->assertEquals(1, count($states));
		$this->assertEquals(JobState::DONE, $states[0]->jobstate_uid);
	}

	public function testBuildInBackGround() {
		$project = Fixtures::getProject();
		$project->save();
		$job = Fixtures::getJob($project);
		$job->cmd = 'echo "Hello World"';
		$job->save();

		$project->buildInBackGround('me');
		sleep(1);
		$states = $project->getLatestJobResults();
		$this->assertEquals(1, count($states));
		$this->assertEquals(JobState::DONE, $states[0]->jobstate_uid);
	}
}