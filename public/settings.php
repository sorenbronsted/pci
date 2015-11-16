<?php

spl_autoload_register(function($class) {
	$paths = array(
		"../application/model",
		"../application/control",
		"../vendor/ufds/libdatabase/dbobject",
		"../vendor/ufds/libtypes/types",
		"../vendor/ufds/libutil/config",
		"../vendor/ufds/libutil/di",
		"../vendor/ufds/libutil/log",
	);

	foreach($paths as $path) {
		$fullname = __DIR__.'/'.$path.'/'.$class.'.php';
		if (is_file($fullname)) {
			include($fullname);
			return true;
		}
	}
	return false;
});

$dic = DiContainer::instance();
$dic->config = new Config2('/etc/ufds/pci.ini');
$dic->log = Log::createFromConfig();

date_default_timezone_set("Europe/Copenhagen");
openlog("ufds-pci", LOG_PID | LOG_CONS, LOG_LOCAL0);
