<?php
namespace sbronsted;

class JobState extends DbObject {
	const RUNNING = 1;
	const DONE = 2;
	const FAILED = 3;
	
	private static $properties = array(
		'uid' => Property::INT,
		'name' => Property::STRING,
	);

	protected function getProperties() : array {
		return self::$properties;
	}
}