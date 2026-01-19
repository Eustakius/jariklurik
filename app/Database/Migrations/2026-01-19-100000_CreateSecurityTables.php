<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSecurityTables extends Migration
{
    public function up()
    {
        // 1. Security Logs Table
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45', // IPv6 support
            ],
            'user_agent' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'url' => [
                'type'       => 'TEXT',
            ],
            'method' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
            ],
            'status_code' => [
                'type'       => 'INT',
                'constraint' => 3,
            ],
            'response_time' => [
                'type'       => 'FLOAT', // In seconds or ms
                'null'       => true,
            ],
            'payload_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '64', // SHA256
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('ip_address');
        $this->forge->addKey('created_at');
        $this->forge->createTable('security_logs', true);

        // 2. Security Incidents Table (High Level Alerts)
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'log_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // SQLi, XSS, BruteForce, etc.
            ],
            'severity' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default'    => 'medium',
            ],
            'details' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['open', 'investigating', 'resolved', 'false_positive'],
                'default'    => 'open',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('log_id', 'security_logs', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('security_incidents', true);

        // 3. IP Blocklist Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('ip_address');
        $this->forge->createTable('ip_blocklist', true);

        // 4. Security Exceptions (Whitelist)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pattern' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['ip', 'url', 'user_agent'],
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('security_exceptions', true);
    }

    public function down()
    {
        $this->forge->dropTable('security_exceptions');
        $this->forge->dropTable('ip_blocklist');
        $this->forge->dropTable('security_incidents');
        $this->forge->dropTable('security_logs');
    }
}
