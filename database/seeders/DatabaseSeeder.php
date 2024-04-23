<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Faculty;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\User::factory()->create([
            'name' => 'adminn',
            'email' => 'adminn@gmail.com',
            'password' => bcrypt('123456'),
            'address' => 'HaNoi',
            'phone_number' => '0981488355',
            'faculty_id' => 1, // Replace with the appropriate faculty_id
            'role_id' => 1, // Replace with the appropriate role_id
            'designation' => 'Administrator', // Replace with the appropriate designation
            'start_from' => now(),
            'image' => 'default.jpg', // Set image to null
        ]);

        Faculty::create([
            'name' => 'Faculty 1',
            'description' => 'Description of Faculty 1',
        ]);

        Role::create([
            'name' => 'Admin',
            'description' => 'Administrator role',
        ]);

        Permission::create([
            'role_id' => 1, // ID của vai trò Admin
            'name' => json_encode([
                'faculty' => [
                    'can-add' => true,
                    'can-edit' => true,
                    'can-view' => true,
                    'can-delete' => true,
                ],
                'role' => [
                    'can-add' => true,
                    'can-edit' => true,
                    'can-view' => true,
                    'can-delete' => true,
                ],
                'permission' => [
                    'can-add' => true,
                    'can-edit' => true,
                    'can-view' => true,
                    'can-delete' => true,
                ],
                'user' => [
                    'can-add' => true,
                    'can-edit' => true,
                    'can-view' => true,
                    'can-delete' => true,
                ],
                'event' => [
                    'can-add' => true,
                    'can-edit' => true,
                    'can-view' => true,
                    'can-delete' => true,
                ],
                'contribution' => [
                    'can-add' => true,
                    'can-edit' => true,
                    'can-view' => true,
                    'can-delete' => true,
                    'can-download' => true,
                    'can-comment' => true,
                ],
            ]),
        ]);
    }
}
