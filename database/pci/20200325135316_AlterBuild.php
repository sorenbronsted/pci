<?php

class AlterBuild extends Ruckusing_Migration_Base {
	public function up() {
		$this->change_column('build', 'result', 'longbinary', ['null' => true]);
	}//up()

	public function down() {
	}//down()
}
