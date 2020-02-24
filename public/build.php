<?php
namespace sbronsted;

use Exception;
use RuntimeException;

// Must always be in the public directory
chdir(__DIR__);

require_once 'settings.php';

$dic = DiContainer::instance();

try {
	if (empty($_POST['payload'])) {
		throw new RuntimeException('Empty payload');
	}
	Build::create(json_decode($_POST['payload']));

	$cmd = "nohup sudo -iu pci php ".__DIR__."/work.php > /dev/null 2>&1 &";
	$dic->log->debug(__CLASS__,"$cmd");
	shell_exec($cmd);
}
catch(Exception $e) {
	$dic->log->error(__CLASS__, $e->getMessage());
	$dic->log->error(__CLASS__, $e->getTraceAsString());
	$dic->header->out($_SERVER['SERVER_PROTOCOL']. " 500 ".$e->getMessage());
}
echo "OK";

