<?php

class AlterProject1 extends Ruckusing_Migration_Base {
	public function up() {
		$this->add_column('project', 'dir', 'string', array('limit' => 128, 'null' => true));
	}

	public function down() {
		$this->remove_column('project', 'dir');
	}
}
