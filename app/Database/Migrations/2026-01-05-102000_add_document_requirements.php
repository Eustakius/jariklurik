<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDocumentRequirements extends Migration
{
    public function up()
    {
        // Add required_documents to job_vacancy
        // Stores JSON array of required file keys e.g. ["cv", "language_cert"]
        $this->forge->addColumn('job_vacancy', [
            'required_documents' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        // Add documents to applicant
        // Stores JSON object of uploaded paths e.g. {"cv": "path/to/cv.pdf"}
        $this->forge->addColumn('applicant', [
            'documents' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('job_vacancy', 'required_documents');
        $this->forge->dropColumn('applicant', 'documents');
    }
}
