<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeviceFingerprintToWebVisitors extends Migration
{
    public function up()
    {
        $this->forge->addColumn('web_visitors', [
            'device_fingerprint' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'       => true,
                'after'      => 'user_agent',
            ],
            'visit_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'last_activity',
            ],
        ]);

        // Add composite index for unique visitor lookups
        $this->forge->addKey(['ip_address', 'device_fingerprint', 'visit_date'], false, false, 'idx_unique_visitor');
        $this->db->query('CREATE INDEX idx_unique_visitor ON web_visitors(ip_address, device_fingerprint, visit_date)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX idx_unique_visitor ON web_visitors');
        $this->forge->dropColumn('web_visitors', ['device_fingerprint', 'visit_date']);
    }
}
