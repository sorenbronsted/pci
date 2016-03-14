<?php
require_once 'vendor/autoload.php' ;
require_once 'vendor/ufds/libutil/di/DiContainer.php' ;
require_once 'vendor/ufds/libutil/config/Config2.php' ;

date_default_timezone_set("Europe/Copenhagen");
$dic = DiContainer::instance();
$dic->config = new Config2('/etc/ufds/pci.ini');

//----------------------------
// DATABASE CONFIGURATION
//----------------------------
return array(
  'db' => array(
      'development' => array(
        'type'      => $dic->config->defaultDb_driver,
        'host'      => $dic->config->defaultDb_host,
        'port'      => $dic->config->defaultDb_port,
        'database'  => $dic->config->defaultDb_name,
        'user'      => $dic->config->defaultDb_user,
        'password'  => $dic->config->defaultDb_password,
        'charset'   => $dic->config->defaultDb_charset
      ),
    ),
  'ruckusing_base' => dirname(__FILE__) . '/vendor/ruckusing/ruckusing-migrations',
  'migrations_dir' => RUCKUSING_WORKING_BASE . '/database',
  'db_dir' => RUCKUSING_WORKING_BASE . '/db',
  'log_dir' => '/tmp/logs',
);

?>
