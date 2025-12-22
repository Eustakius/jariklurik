<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserModel;

class CheckUser extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'check:user';
    protected $description = 'Check user 2FA secret';
    protected $usage       = 'check:user [username]';
    protected $arguments   = [
        'username' => 'The username to check',
    ];

    public function run(array $params)
    {
        $username = $params[0] ?? null;

        if (empty($username)) {
            CLI::error('Username required.');
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            CLI::error('User not found: ' . $username);
            return;
        }

        CLI::write("User ID: " . $user->id);
        CLI::write("Username: " . $user->username);
        
        $secret = $user->google2fa_secret;
        CLI::write("Raw Secret from Model: (" . var_export($secret, true) . ")");
        
        if (empty($secret)) {
             CLI::write("Status: 2FA IS DISABLED (Secret is empty)", 'green');
        } else {
             CLI::write("Status: 2FA IS ENABLED", 'red');
        }
    }
}
