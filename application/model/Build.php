<?php
namespace sbronsted;

use RuntimeException;

class Build extends ModelObject {

	const OK = 0;
	const READY = 1;
	const RUNNING = 2;
	const FAILED = 3;

	private static $properties = [
		'uid' => Property::INT,
		'ref' => Property::STRING,
		'repo' => Property::STRING,
		'user' => Property::STRING,
		'name' => Property::STRING,
		'email' => Property::STRING,
		'clone_url' => Property::STRING,
		'avatar_url' => Property::STRING,
		'result' => Property::STRING,
		'state' => Property::INT,
		'start' => Property::TIMESTAMP,
		'stop' => Property::TIMESTAMP,
		'created' => Property::TIMESTAMP,
	];

	private static $mandatories = [
		'ref', 'repo', 'user', 'state', 'clone_url'
	];

	public static function getAll(array $orderby = array()): array {
		return parent::getWhere('1=1 order by created desc limit 50');
	}

	public static function create(object $payload) : Build {
		$build = new Build();
		$build->ref = $payload->ref;
		$build->repo = $payload->repository->name;
		$build->clone_url = $payload->repository->clone_url;
		$build->user = $payload->repository->owner->username;
		$build->name = $payload->repository->owner->full_name;
		$build->email = $payload->repository->owner->email;
		$build->avatar_url = $payload->repository->owner->avatar_url;
		$build->state = self::READY;
		$build->created = new Timestamp();
		$build->save();
		return $build;
	}

	public function run() {
		// git pull or clone
		$branch = basename($this->ref);
		$dic = DiContainer::instance();
		$dir = $dic->config->build_root.'/'.$this->user.'/'.$this->repo.'/'.$branch;
		$this->ensureDir($dir);
		if (!file_exists($dir.'/.git')) {
			$cmd = "git clone --branch $branch $this->clone_url";
		}
		else {
			$cmd = 'git pull';
		}
		chdir($dir);

		$this->result = '';
		$this->state = self::RUNNING;
		$this->start = new Timestamp();
		$this->save();
		try {
			$this->result = "cd: $dir\n";
			$this->result .= "$cmd\n";
			$this->result .= $dic->executer->run($cmd);

			if (!file_exists('Makefile')) {
				throw new RuntimeException("No makefile, so nothing to do");
			}

			$this->result .= "make\n";
			$this->result .= $dic->executer->run('make');
			$this->state = self::OK;
			$this->stop = new Timestamp();
			$this->save();
		}
		catch (RuntimeException $e) {
			$this->result .= $e->getMessage()."\n";
			$this->state = self::FAILED;
			$this->stop = new Timestamp();
			$this->save();
		}
	}

	public function getMandatories(): array {
		return self::$mandatories;
	}

	protected function getProperties(): array {
		return self::$properties;
	}

	private function ensureDir(string $dir) {
		if (!file_exists($dir)) {
			if ((@mkdir($dir, 0755, true)) === false) {
				throw new RuntimeException('Failed to create dir: ' . $dir);
			}
		}
	}
}