<?php

class AddJobResult extends Ruckusing_Migration_Base {
	private $table = 'jobresult';

	public function up() {
		$t = $this->create_table($this->table, array('id' => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
		$t->column('uid', 'primary_key', array('primary_key' => true, 'auto_increment' => true, 'unsigned' => true, 'null' => false));
		$t->column('start','datetime',array('null' => true));
		$t->column('stop','datetime',array('null' => true));
		$t->column('log','text',array('null' => true));
		$t->column('user','string',array('string' => 64, 'null' => true));
		$t->column('build_id','integer',array('null' => false));
		$t->column('job_uid','integer',array('null' => false));
		$t->column('jobstate_uid','integer',array('null' => false));
		$t->finish();
	}

	public function down() {
		$this->drop_table($this->table);
	}
}
