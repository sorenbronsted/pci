#!/usr/bin/php
<?php
if ($argc != 4) {
  print("Usage: $argv[0] <ini-file> <section> <variable name>\n");
  exit(1);
}
if (!file_exists($argv[1])) {
  exit(2);
}
$a = parse_ini_file($argv[1],true);
if (isset($a[$argv[2]]) && isset($a[$argv[2]][$argv[3]])) {
  print($a[$argv[2]][$argv[3]]."\n");
}

