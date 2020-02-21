<?php
namespace sbronsted;

class Worker {
	const TMP_PCI_LOCK = '/tmp/pci_worker.lock';

	public static function run() {
		$dic = DiContainer::instance();
		if (file_exists(self::TMP_PCI_LOCK)) {
			$dic->log->debug(__CLASS__, 'Allready running, bye');
			return;
		}
		touch(self::TMP_PCI_LOCK);

		try {
			$builds = Build::getBy(['state' => Build::READY]);
			while (count($builds) > 0) {
				foreach ($builds as $build) {
					$dic->log->debug(__CLASS__, 'Running build: '.$build->uid);
					$build->run();
				}
				$builds = Build::getBy(['state' => Build::READY]);
			}
		}
		finally {
			unlink(self::TMP_PCI_LOCK);
		}
		$dic->log->debug(__CLASS__, 'Finished working');
	}
}