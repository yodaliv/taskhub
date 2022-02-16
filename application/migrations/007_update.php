<?php
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
    }
    public function up(){
        $this->dbforge->add_field([
			'id' => [
				'type'           => 'INT',
				'constraint'     => '11',
				'auto_increment' => TRUE
			],
			'title' => [
				'type'           => 'VARCHAR',
				'constraint'     => '512'
            ],
            'max_workspaces' => [
                'type'           => 'INT',
                'constraint'     => '11'
			],
			'max_employees' => [
                'type'           => 'INT',
                'constraint'     => '11'
			],
			'max_storage_size' => [
				'type'           => 'FLOAT'
			],
			'storage_unit' => [
                'type'           => 'VARCHAR',
                'constraint'     => '28'
            ],
            'plan_type' => [
                'type'           => 'VARCHAR',
                'constraint'     => '28'
            ],
            'position_no' => [
				'type'           => 'INT',
                'constraint'     => '11'
            ],
            'monthly_price' => [
                'type'           => 'FLOAT',
                'NULL' 			 => TRUE
            ],
            'annual_price' => [
                'type'           => 'FLOAT',
                'NULL' 			 => TRUE
            ],
            'modules' => [
				'type'           => 'TEXT'
            ],
            'description' => [
                'type'           => 'TEXT',
                'NULL' 			 => TRUE
            ],
            'status' => [
				'type'			=> 'TINYINT',
				'default'		=> '1'
			],
    		'date_created TIMESTAMP default CURRENT_TIMESTAMP',
		]);
		$this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('packages');

        $this->dbforge->add_field([
			'id' => [
				'type'           => 'INT',
				'constraint'     => '11',
				'auto_increment' => TRUE
			],
			'user_id' => [
				'type'           => 'INT',
				'constraint'     => '11'
            ],
    		'date_created TIMESTAMP default CURRENT_TIMESTAMP',
		]);
		$this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user_companies');
    }
}
