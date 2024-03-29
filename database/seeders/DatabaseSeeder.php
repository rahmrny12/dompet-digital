<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\StudentParent::truncate();
        \App\Models\Student::truncate();
        \App\Models\User::truncate();
        \App\Models\Classroom::truncate();
        \App\Models\Transaction::truncate();
        Schema::enableForeignKeyConstraints();

        $user1 = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'role' => 'admin',
        ]);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'Yahya',
            'email' => 'yahya@example.com',
            'username' => 'yahya',
            'role' => 'teacher',
        ]);

        $user3 = \App\Models\User::factory()->create([
            'name' => 'Ryan',
            'email' => 'ryan@example.com',
            'username' => 'ryan',
            'role' => 'student',
        ]);

        $user4 = \App\Models\User::factory()->create([
            'name' => 'Petit',
            'email' => 'petit@example.com',
            'username' => 'petit',
            'role' => 'student',
        ]);

        $user5 = \App\Models\User::factory()->create([
            'name' => 'Wali Murid',
            'email' => 'walimurid@example.com',
            'username' => 'walimurid',
            'role' => 'parent',
        ]);

        $teacher1 = \App\Models\Teacher::create([
            'nip' => 123123123,
            'name' => $user2->name,
            'email' => fake()->unique()->safeEmail(),
            'gender' => 'L',
            'phone' => fake()->phoneNumber(),
            'user_id' => $user2->id,
            'foto' => null,
        ]);

        $classroom1 = \App\Models\Classroom::create([
            'name' => 'XII TESTING',
            'teacher_id' => $teacher1->id,
        ]);

        $parent1 = \App\Models\StudentParent::create([
            'name' => $user5->name,
            'email' => $user5->email,
            'phone' => fake()->phoneNumber(),
            'user_id' => $user5->id,
        ]);

        \App\Models\Student::create([
            'nisn' => 123123123,
            'name' => $user3->name,
            'classroom_id' => $classroom1->id,
            'user_id' => $user3->id,
            'parent_id' => $parent1->id,
            'nfc_id' => 123123,
        ]);

        \App\Models\Student::create([
            'nisn' => 123123124,
            'name' => $user4->name,
            'classroom_id' => $classroom1->id,
            'user_id' => $user4->id,
            'parent_id' => $parent1->id,
            'nfc_id' => 123124,
        ]);

        \App\Models\Transaction::factory(10)->create([
            'student_id' => 1,
        ]);

        \App\Models\Transaction::factory(10)->create([
            'student_id' => 2,
        ]);
    }
}
