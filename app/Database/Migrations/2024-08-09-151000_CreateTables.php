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

        // balances table
        $this->forge->addField([
            'user_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'balance' => [
                'type' => 'INT',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->createTable('balances');

        //top_ups table
        $this->forge->addField([
            'top_up_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'user_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'amount_top_up' => [
                'type' => 'INT',
                'null' => false,
            ],
            'balance_before' => [
                'type' => 'INT',
                'null' => false,
            ],
            'balance_after' => [
                'type' => 'INT',
                'null' => false,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'created_date' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('top_up_id', true);
        $this->forge->createTable('top_ups');

        //payments table
        $this->forge->addField([
            'payment_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'user_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'amount' => [
                'type' => 'INT',
                'null' => false,
            ],
            'remarks' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'balance_before' => [
                'type' => 'INT',
                'null' => false,
            ],
            'balance_after' => [
                'type' => 'INT',
                'null' => false,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'created_date' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('payment_id', true);
        $this->forge->createTable('payments');

        //transfers table
        $this->forge->addField([
            'transfer_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'user_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'target_user_id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'amount' => [
                'type' => 'INT',
                'null' => false,
            ],
            'remarks' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'balance_before' => [
                'type' => 'INT',
                'null' => false,
            ],
            'balance_after' => [
                'type' => 'INT',
                'null' => false,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'created_date' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('transfer_id', true);
        $this->forge->createTable('transfers');
    }

    public function down()
    {
        $this->forge->dropTable('users');
        $this->forge->dropTable('refresh_tokens');
        $this->forge->dropTable('balances');
        $this->forge->dropTable('top_ups');
        $this->forge->dropTable('transfers');
        $this->forge->dropTable('payments');
    }
}
