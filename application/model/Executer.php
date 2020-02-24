<?php
namespace sbronsted;

class Executer implements IExecuter {
	public function run(string $cmd) : string {
		$result = '';
		$ph = popen($cmd . " 2>&1", 'r');
		if ($ph === false) {
			throw new ExecuteException('popen failed cmd:'.$cmd, -1);
		}

		while (!feof($ph)) {
			$result .= fread($ph, 4096);
		}
		$retval = pclose($ph);
		if ($retval != 0) {
			throw new ExecuteException($result, $retval);
		}
		return $result;
	}
}