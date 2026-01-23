<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeviceTypeToWebVisitors extends Migration
{
    public function up()
    {
        $this->forge->addColumn('web_visitors', [
            'device_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Desktop', 'Mobile', 'Tablet', 'Unknown'],
                'default'    => 'Unknown',
                'null'       => false,
                'after'      => 'device_fingerprint',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('web_visitors', 'device_type');
    }
}
