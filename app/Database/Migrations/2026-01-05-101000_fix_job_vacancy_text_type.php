<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixJobVacancyTextType extends Migration
{
    public function up()
    {
        // Change description and requirement to LONGTEXT to support base64 images
        $fields = [
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'requirement' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('job_vacancy', $fields);

        // Also ensure charset is utf8mb4 for these columns explicitly
        $this->db->query("ALTER TABLE job_vacancy MODIFY description LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->db->query("ALTER TABLE job_vacancy MODIFY requirement LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function down()
    {
        // Revert to TEXT (not recommended if data exceeds 64KB, but for rollback)
        $fields = [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'requirement' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('job_vacancy', $fields);
    }
}
