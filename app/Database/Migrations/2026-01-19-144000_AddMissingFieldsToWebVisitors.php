<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToWebVisitors extends Migration
{
    public function up()
    {
        // Check if table exists first
        if (!$this->db->tableExists('web_visitors')) {
            // Create the table with all fields
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'ip_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => '45',
                ],
                'user_agent' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                ],
                'page_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => '500',
                ],
                'platform' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                ],
                'referer' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ],
                'last_activity' => [
                    'type' => 'DATETIME',
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
            $this->forge->createTable('web_visitors');
        } else {
            // Add missing fields if table exists
            $fields = [
                'page_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => '500',
                    'null' => true,
                ],
                'last_activity' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ];
            
            foreach ($fields as $fieldName => $fieldConfig) {
                if (!$this->db->fieldExists($fieldName, 'web_visitors')) {
                    $this->forge->addColumn('web_visitors', [$fieldName => $fieldConfig]);
                }
            }
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('page_url', 'web_visitors')) {
            $this->forge->dropColumn('web_visitors', 'page_url');
        }
        if ($this->db->fieldExists('last_activity', 'web_visitors')) {
            $this->forge->dropColumn('web_visitors', 'last_activity');
        }
    }
}
