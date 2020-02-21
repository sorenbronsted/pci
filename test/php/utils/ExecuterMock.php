<?php
namespace sbronsted;

class ExecuterMock implements IExecuter {
	public $calls = [];

	public function run(string $cmd): string {
		$this->calls[] = $cmd;
		return "OK\n";
	}
}