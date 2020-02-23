<?php

namespace sbronsted;

use stdClass;

class Event extends ModelObject {
	const CREATE = 1;
	const UPDATE = 2;
	const DELETE = 3;

	private static $properties = [
		'uid' => Property::INT,
		'build_uid' => Property::INT,
		'body' => Property::STRING,
		'created' => Property::TIMESTAMP,
		'operation' => Property::INT,
	];

	public static function add(Build $build, int $operation, stdClass $body = null) {
		$event = new Event();
		$event->created = new Timestamp();
		$event->operation = $operation;
		$event->build_uid = $build->uid;
		$event->body = is_null($body) ? $body : json_encode($body);
		$event->save();
	}

	public static function run() {
		header("Content-Type: text/event-stream");

		$last = new Timestamp();
		while (connection_aborted() == 0) {
			$now = new Timestamp();

			$events = Event::getWhere("created > ?", [$last]);
			foreach ($events as $event) {
				echo "data: " . $event->body . "\n\n";
			}
			while (ob_get_level() > 0) {
				ob_end_flush();
			}
			flush();
			sleep(5);
			$last = $now;
		}
	}

	protected function getProperties(): array {
		return self::$properties;
	}
}