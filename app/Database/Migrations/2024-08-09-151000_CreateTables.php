<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
    public function up()
    {
        // users table
        $this->forge->addField([
            'user_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false,
                'unique' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'pin' => [
                'type' => 'VARCHAR',
                'constraint' => '6',
                'null' => false,
            ],
            'created_date' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'updated_date' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->createTable('users');

        // refresh_tokens table
        $this->forge->addField([
            'user_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'token' => [
                'type' => 'TEXT',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->createTable('refresh_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('users');
        $this->forge->dropTable('refresh_tokens');
    }
}
