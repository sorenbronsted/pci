<?php
namespace ufds;

// Must always be in the public directory
use Exception;
use RuntimeException;

chdir(__DIR__);

require_once 'settings.php';

if (php_sapi_name() == 'cli') {
	try {
		if (count($argv) != 3) {
			throw new RuntimeException('Wrong number of arguments');
		}
		$dic = DiContainer::instance();
		$dic->log->debug(basename(__FILE__), "building project $argv[1] for user $argv[2]");
		Project::build($argv[1], $argv[2]);
	}
	catch(Exception $e) {
		$dic->log->error(basename(__FILE__), $e->getMessage());
	}
}
