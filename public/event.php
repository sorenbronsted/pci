<?php
namespace sbronsted;
// https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events

require_once 'settings.php';

header("Content-Type: text/event-stream");

$last = new Timestamp();
while (connection_aborted() == 0) {
	$now = new Timestamp();

	// This is for detecting client disconnect
	echo "event: ping\n\n";

	$events = Event::getWhere("created >= ?", [$last]);
	foreach ($events as $event) {
		echo "data: ".$event->body."\n\n";
	}

	ob_flush();
	flush();
	sleep(5);
	$last = $now;
}