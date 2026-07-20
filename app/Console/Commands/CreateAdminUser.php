<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin {email} {password} {name}';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Check if user exists
        $exists = DB::table('users')->where('email', $email)->exists();
        
        if ($exists) {
            $this->error("❌ User with email {$email} already exists!");
            return 1;
        }

        // Create admin user
        DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("✅ Admin user created successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info("Role: admin");
        
        return 0;
    }
}
