<?php
namespace sbronsted;

$loader = require dirname(__DIR__).'/vendor/autoload.php'; // Use composer autoloading
$loader->addPsr4('sbronsted\\', 'test/php/utils');

date_default_timezone_set("Europe/Copenhagen");
openlog("pci", LOG_PID | LOG_CONS, LOG_LOCAL0);

$dic = DiContainer::instance();
$dic->config = new Config2(__DIR__.'/pci.ini');
$dic->log = Log::createFromConfig();

