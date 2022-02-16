<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_users_alter_table extends CI_Migration
{
    public function up()
    {
        /* adding new fields in users table */
        $fields = array(
            'logo' => array(
				'type' => 'VARCHAR',
				'constraint' => '256',
				'null' => TRUE,
				'after' => 'company'
			),
            'half_logo' => array(
				'type' => 'VARCHAR',
				'constraint' => '256',
				'null' => TRUE,
				'after' => 'logo'
			),
            'favicon' => array(
				'type' => 'VARCHAR',
				'constraint' => '256',
				'null' => TRUE,
				'after' => 'half_logo'
			),
            'email_config' => array(
				'type' => 'VARCHAR',
				'constraint' => '256',
				'null' => TRUE,
				'after' => 'profile'
			),
        );
        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        // Drop columns >> $this->dbforge->drop_column('table_name', 'column_to_drop');

        $this->dbforge->drop_column('users', 'logo');
        $this->dbforge->drop_column('users', 'half_logo');
        $this->dbforge->drop_column('users', 'favicon');
        $this->dbforge->drop_column('users', 'email_config');
        
    }
}