<?php
namespace sbronsted;

// Must always be in the public directory
use Exception;
use RuntimeException;

chdir(__DIR__);

require_once 'settings.php';

if (php_sapi_name() == 'cli') {
	$dic = DiContainer::instance();
	try {
		$dic->log->debug(basename(__FILE__), "Starting worker");
		Worker::run();
	}
	catch (Exception $e) {
		$dic->log->error(basename(__FILE__), $e->getMessage());
	}
}
