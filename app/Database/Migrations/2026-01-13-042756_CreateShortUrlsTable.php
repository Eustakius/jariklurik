<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShortUrlsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'short_code' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'unique' => true,
            ],
            'full_url' => [
                'type' => 'TEXT',
            ],
            'clicks' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('short_code');
        $this->forge->createTable('short_urls');
    }

    public function down()
    {
        $this->forge->dropTable('short_urls');
    }
}
