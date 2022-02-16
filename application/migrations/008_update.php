<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_update extends CI_Migration
{
    // and I think user_type_id ni jagya a simply user_id rakhi daia to chalse
    public function up()
    {
        /* adding new table ticket_types */
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
            'workspace_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'user_type' => [
                'type'           => 'VARCHAR',
                'constraint'     => '28'
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'status' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'DEFAULT'     => '1',
            ],
            'date_created TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ticket_types');

        /* adding new table tickets */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'workspace_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'ticket_type_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'subject' => [
                'type' => 'TEXT',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint'     => '128',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'DEFAULT'     => '1',
            ],
            'user_ids' => [
                'type' => 'TEXT',
                'NULL'     => true
            ],
            'client_ids' => [
                'type' => 'TEXT',
                'NULL'     => true
            ],
            'last_updated TIMESTAMP on update CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'date_created TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tickets');

        /* adding new table ticket_messages */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'user_type' => [
                'type' => 'VARCHAR',
                'constraint'     => '28',
                'NULL'     => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'ticket_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'message' => [
                'type' => 'TEXT',
                'NULL'     => true,
            ],
            'attachments' => [
                'type' => 'TEXT',
                'NULL'     => true,
            ],
            'last_updated TIMESTAMP on update CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'date_created TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ticket_messages');
    }

    public function down()
    {
        // Drop table 
        // Drop columns >> $this->dbforge->drop_column('table_name', 'column_to_drop');
        $this->dbforge->drop_table('ticket_messages');
        $this->dbforge->drop_table('tickets');
        $this->dbforge->drop_table('ticket_types');
    }
}
