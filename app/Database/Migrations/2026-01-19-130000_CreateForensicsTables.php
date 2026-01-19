<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateForensicsTables extends Migration
{
    public function up()
    {
        // 1. security_fingerprints - Stores device data
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'canvas_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'device_hash' => [
                 'type' => 'VARCHAR', // Composite hash
                 'constraint' => 255,
                 'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'local_ips' => [
                'type' => 'TEXT', // JSON array of leaked IPs
                'null' => true,
            ],
            'screen_resolution' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'timezone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'raw_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('canvas_hash');
        $this->forge->addKey('ip_address');
        $this->forge->createTable('security_fingerprints');
    }

    public function down()
    {
        $this->forge->dropTable('security_fingerprints');
    }
}
