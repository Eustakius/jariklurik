<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScanResultsTables extends Migration
{
    public function up()
    {
        // Table: scan_results
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'scan_date' => [
                'type' => 'DATETIME',
            ],
            'target_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'scan_type' => [
                'type' => 'ENUM',
                'constraint' => ['comprehensive', 'quick', 'custom'],
                'default' => 'comprehensive',
            ],
            'total_vulnerabilities' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'critical_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'high_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'medium_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'low_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'info_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['running', 'completed', 'failed'],
                'default' => 'running',
            ],
            'results_json' => [
                'type' => 'LONGTEXT',
                'null' => true,
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
        $this->forge->createTable('scan_results');

        // Table: scan_vulnerabilities
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'scan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'severity' => [
                'type' => 'ENUM',
                'constraint' => ['critical', 'high', 'medium', 'low', 'info'],
                'default' => 'info',
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'proof_of_concept' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'remediation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'affected_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('scan_id');
        $this->forge->addForeignKey('scan_id', 'scan_results', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('scan_vulnerabilities');
    }

    public function down()
    {
        $this->forge->dropTable('scan_vulnerabilities');
        $this->forge->dropTable('scan_results');
    }
}
