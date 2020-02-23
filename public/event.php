<?php
namespace sbronsted;
// https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events

require_once 'settings.php';

header("Content-Type: text/event-stream");

$last = new Timestamp();
while (connection_aborted() == 0) {
	$now = new Timestamp();

	$events = Event::getWhere("created > ?", [$last]);
	foreach ($events as $event) {
		echo "data: ".$event->body."\n\n";
	}
	while (ob_get_level() > 0) {
		ob_end_flush();
	}
	flush();
	sleep(5);
	$last = $now;
}