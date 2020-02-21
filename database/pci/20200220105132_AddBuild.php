<?php

class AddBuild extends Ruckusing_Migration_Base {
	private $table = 'build';

	public function up() {
		$t = $this->create_table($this->table, array('id' => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
		$t->column('uid', 'primary_key', array('primary_key' => true, 'auto_increment' => true, 'unsigned' => true, 'null' => false));
		$t->column('ref','string', array('limit' => 512, 'null' => false));
		$t->column('repo','string', array('limit' => 512, 'null' => false));
		$t->column('clone_url','string', array('limit' => 512, 'null' => false));
		$t->column('user','string', array('limit' => 16, 'null' => false));
		$t->column('name','string', array('limit' => 128, 'null' => true));
		$t->column('email','string', array('limit' => 256, 'null' => true));
		$t->column('avatar_url','string', array('limit' => 512, 'null' => true));
		$t->column('result','text', array('limit' => 512, 'null' => true));
		$t->column('state','integer', array('null' => false));
		$t->column('start','datetime', array('null' => true));
		$t->column('stop','datetime', array('null' => true));
		$t->column('created','datetime', array('null' => false));
		$t->finish();

		$this->drop_table('buildidgenerator');
		$this->drop_table('job');
		$this->drop_table('jobresult');
		$this->drop_table('project');
		$this->drop_table('jobstate');
	}//up()

	public function down() {
		$this->drop_table($this->table);
	}//down()
}
