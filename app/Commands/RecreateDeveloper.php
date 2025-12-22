<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserModel;
use Myth\Auth\Models\GroupModel;

class RecreateDeveloper extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'recreate:developer';
    protected $description = 'Deletes and recreates the developer user with same hash';

    public function run(array $params)
    {
        $username = 'developer';
        $userModel = new UserModel();
        $groupModel = new GroupModel();

        // 1. Get current user data
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            CLI::error('User developer not found!');
            // Create fresh?
            return;
        }

        $hash = $user->password_hash;
        $email = $user->email;
        $groups = $groupModel->getGroupsForUser($user->id);

        CLI::write("Found user $username (ID: {$user->id})");
        CLI::write("Saving Password Hash...");
        CLI::write("Saving Groups: " . implode(', ', array_column($groups, 'name')));

        // 2. Delete
        CLI::write("Deleting user...");
        $userModel->delete($user->id, true); // Hard delete to allow reusing username

        // 3. Recreate
        CLI::write("Creating new user...");
        
        $newUser = new \App\Entities\User();
        $newUser->email = $email;
        $newUser->username = $username;
        $newUser->password_hash = $hash;
        $newUser->active = 1;
        $newUser->activate_hash = null;
        $newUser->status = null;
        $newUser->status_message = null;

        if (!$userModel->save($newUser)) {
             CLI::error("Failed to create user!");
             print_r($userModel->errors());
             return;
        }

        $newId = $userModel->getInsertID();
        CLI::write("New User Created (ID: $newId)");

        // 4. Restore Groups
        foreach ($groups as $group) {
            $groupModel->addUserToGroup($newId, $group['group_id']);
            CLI::write("Restored to group: " . $group['name']);
        }

        CLI::write("Done! Session tokens should be invalid now.", 'green');
    }
}
