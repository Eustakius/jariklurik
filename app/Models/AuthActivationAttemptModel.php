<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthActivationAttemptModel extends Model
{
    protected $table      = 'auth_activation_attempts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id',
        'ip_address',
        'user_agent',
        'token',
        'created_at'
    ];

    public function findToken($payload): ?object
    {
        $result = $this->where([
            'ip_address' => $payload->ip_address,
            'user_agent' => $payload->user_agent,
        ])
            ->orderBy('id', 'DESC')
            ->first();

        return $result ? (object) $result : null;
    }
}
