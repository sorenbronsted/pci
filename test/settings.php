<?php

$loader = require 'vendor/autoload.php'; // Use composer autoloading
$loader->addClassMap(array(
        'BaseCrud' => 'test/php/utils/BaseCrud.php',
        'Fixtures' => 'test/php/utils/Fixtures.php',
));

date_default_timezone_set("Europe/Copenhagen");
openlog("ufds-pci", LOG_PID | LOG_CONS, LOG_LOCAL0);

$dic = DiContainer::instance();
$dic->config = new Config2(__DIR__.'/pci.ini');
$dic->log = Log::createFromConfig();

