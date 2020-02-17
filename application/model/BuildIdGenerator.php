<?php
namespace sbronsted;

class BuildIdGenerator extends ModelObject {

	private static $properties = array(
		'uid' => Property::INT,
		'project_uid' => Property::INT,
		'build_id' => Property::INT,
	);

	private static $mandatories = array(
		'project_uid', 'build_id',
	);

	public static function draw($projectUid) {
		try {
			$big = self::getOneBy(array('project_uid' => $projectUid));
		}
		catch (NotFoundException $e) {
			$big = new BuildIdGenerator();
			$big->project_uid = $projectUid;
			$big->build_id = 0;
		}
		$big->build_id += 1;
		$big->save();
		return $big->build_id;
	}

	public function getMandatories() : array {
		return self::$mandatories;
	}

	protected function getProperties() : array {
		return self::$properties;
	}
}