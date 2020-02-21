<?php
namespace sbronsted;

require 'test/settings.php';

use PHPUnit\Framework\TestCase;

class ExecuterTest extends TestCase {

	public function testRun() {
		$executer = new Executer();
		$result = $executer->run("ls test/pci.ini");
		$this->assertEquals(1, preg_match('/pci.ini/',$result));
	}
}
