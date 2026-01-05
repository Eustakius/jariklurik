<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixEncoding extends Migration
{
    public function up()
    {
        // Convert Database Character Set
        $this->db->query("ALTER DATABASE " . $this->db->database . " CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci");

        // Convert Tables
        $tables = ['job_vacancy', 'applicant', 'companies', 'countries', 'users'];
        
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->query("ALTER TABLE " . $table . " CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
        }
    }

    public function down()
    {
        // No revert needed usually for charset upgrades, but technically we could go back to latin1 or utf8
        // For now, we leave it empty as this is a fix.
    }
}
