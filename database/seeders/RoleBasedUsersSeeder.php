<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleBasedUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Library Admin',
                'email' => 'admin@library.com',
                'password' => Hash::make('password'),
                'phone' => '+880171234567',
                'address' => 'Dhaka University Campus, Dhaka-1000',
                'member_type' => 'staff',
                'status' => 'approved',
                'approved_at' => now(),
                'role' => 'Admin'
            ],
            [
                'name' => 'Head Librarian',
                'email' => 'librarian@library.com',
                'password' => Hash::make('password'),
                'phone' => '+880171234568',
                'address' => 'Gulshan-2, Dhaka-1212',
                'member_type' => 'staff',
                'status' => 'approved',
                'approved_at' => now(),
                'role' => 'Librarian'
            ],
            [
                'name' => 'আহমেদ হাসান',
                'email' => 'ahmed@student.du.ac.bd',
                'password' => Hash::make('password'),
                'phone' => '+880171234569',
                'address' => 'ঢাকা বিশ্ববিদ্যালয়, কার্জন হল',
                'member_type' => 'student',
                'status' => 'approved',
                'approved_at' => now(),
                'role' => 'Member'
            ],
            [
                'name' => 'ফাতেমা খাতুন',
                'email' => 'fatema@teacher.du.ac.bd',
                'password' => Hash::make('password'),
                'phone' => '+880171234570',
                'address' => 'ধানমন্ডি, ঢাকা-১২০৫',
                'member_type' => 'teacher',
                'status' => 'approved',
                'approved_at' => now(),
                'role' => 'Member'
            ],
            [
                'name' => 'রহিম উদ্দিন',
                'email' => 'rahim@public.com',
                'password' => Hash::make('password'),
                'phone' => '+880171234571',
                'address' => 'পুরান ঢাকা, ঢাকা-১১০০',
                'member_type' => 'public',
                'status' => 'approved',
                'approved_at' => now(),
                'role' => 'Member'
            ],
            [
                'name' => 'John Smith',
                'email' => 'john@international.com',
                'password' => Hash::make('password'),
                'phone' => '+880171234572',
                'address' => 'Banani, Dhaka-1213',
                'member_type' => 'public',
                'status' => 'approved',
                'approved_at' => now(),
                'role' => 'Member'
            ],
            [
                'name' => 'সালমা আক্তার',
                'email' => 'salma@pending.com',
                'password' => Hash::make('password'),
                'phone' => '+880171234573',
                'address' => 'মিরপুর, ঢাকা-১২১৬',
                'member_type' => 'student',
                'status' => 'pending',
                'approved_at' => null,
                'role' => null
            ],
            [
                'name' => 'করিম মিয়া',
                'email' => 'karim@rejected.com',
                'password' => Hash::make('password'),
                'phone' => '+880171234574',
                'address' => 'উত্তরা, ঢাকা-১২৩০',
                'member_type' => 'public',
                'status' => 'rejected',
                'rejection_reason' => 'Invalid documentation provided',
                'approved_at' => null,
                'role' => null
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::create($userData);
            
            if ($role) {
                $user->assignRole($role);
            }
        }
    }
}
