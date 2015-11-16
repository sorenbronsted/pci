<?php

class AddJobState extends Ruckusing_Migration_Base {
	private $table = 'jobstate';

	public function up() {
		$t = $this->create_table($this->table, array('id' => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
		$t->column('uid', 'primary_key', array('primary_key' => true, 'auto_increment' => true, 'unsigned' => true, 'null' => false));
		$t->column('name', 'string', array('limit' => 64, 'null' => false));
		$t->finish();

		$this->execute("insert into jobstate(uid, name) values(1, 'Running')");
		$this->execute("insert into jobstate(uid, name) values(2, 'Done')");
		$this->execute("insert into jobstate(uid, name) values(3, 'Failed')");
	}

	public function down() {
		$this->drop_table($this->table);
	}
}
