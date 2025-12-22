<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailQueueModel extends Model
{
    protected $table      = 'email_queue';
    protected $primaryKey = 'id';

    protected $allowedFields = [
                'id',
        'to_email',
        'from_email',
        'subject',
        'body',
        'error',
        'status',
        'created_at',
        'sent_at'
    ];
}