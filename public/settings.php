<?php
namespace sbronsted;

use PHPMailer\PHPMailer\PHPMailer;

$loader = require dirname(__DIR__).'/vendor/autoload.php'; // Use composer autoloading

$dic = DiContainer::instance();
$dic->config = new Config2('/etc/ufds/pci.ini');
$dic->log = Log::createFromConfig();
$dic->executer = new Executer();
$dic->header = new Header(); // Required by this library
$dic->mailer = new PHPMailer(true);

date_default_timezone_set("Europe/Copenhagen");
openlog("pci", LOG_PID | LOG_CONS, LOG_LOCAL0);
