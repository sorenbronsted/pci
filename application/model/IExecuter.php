<?php
namespace sbronsted;

interface IExecuter {
	public function run(string $cmd) : string;
}
