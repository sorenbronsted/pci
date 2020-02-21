<?php
namespace sbronsted;

use RuntimeException;

class Executer implements IExecuter {
	public function run(string $cmd) : string {
		$result = '';
		$ph = popen($cmd . " 2>&1", 'r');
		if ($ph === false) {
			throw new RuntimeException('popen failed cmd:'.$cmd);
		}

		while (!feof($ph)) {
			$result .= fread($ph, 4096);
		}
		$retval = pclose($ph);
		if ($retval == -1) {
			throw new RuntimeException('pclose failed result:'.$cmd);
		}
		return $result;
	}
}