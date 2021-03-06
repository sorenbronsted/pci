<?php
namespace sbronsted;

require 'test/settings.php';

use PHPUnit\Framework\TestCase;

class BuildTest extends TestCase {

	protected function setUp() : void {
		Db::exec(Build::$db, "delete from build");
		Db::exec(Build::$db, "delete from event");
	}

	public function testCreate() {
		$pr = json_decode(Fixtures::pushMasterRequest());
		Build::create($pr);

		$builds = Build::getAll();
		$this->assertEquals(1, count($builds));
		$build = $builds[0];

		$this->assertEquals(Build::READY, $build->state);
		$this->assertNotNull($build->created);
		$this->assertEquals($pr->ref, $build->ref);
		$this->assertEquals($pr->repository->name, $build->repo);
		$this->assertEquals($pr->repository->clone_url, $build->clone_url);
		$this->assertEquals($pr->sender->username, $build->user);
		$this->assertEquals($pr->sender->full_name, $build->name);
		$this->assertEquals($pr->sender->email, $build->email);
		$this->assertEquals($pr->sender->avatar_url, $build->avatar_url);
	}

	public function testRun() {
		$dic = DiContainer::instance();

		$dic->executer = new ExecuterMock();

		$pr = json_decode(Fixtures::pushMasterRequest());
		$build = Build::create($pr);
		$build->run();

		$this->assertEquals(1, count($dic->executer->calls));
		$this->assertEquals(Build::FAILED, $build->state);
		$this->assertEquals(1, preg_match('/clone/', $build->result));
		$this->assertTrue($dic->mailer->isSent);
	}
}
