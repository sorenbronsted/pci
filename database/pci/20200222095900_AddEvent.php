<?php

class AddEvent extends Ruckusing_Migration_Base {
	private $table = 'event';

	public function up() {
		$t = $this->create_table($this->table, array('id' => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
		$t->column('uid', 'primary_key', array('primary_key' => true, 'auto_increment' => true, 'unsigned' => true, 'null' => false));
		$t->column('build_uid','integer', array('null' => false));
		$t->column('body','text', array('null' => false));
		$t->column('created','datetime', array('null' => false));
		$t->column('operation','integer', array('null' => false));
		$t->finish();
	}//up()

	public function down() {
		$this->drop_table($this->table);
	}//down()
}
