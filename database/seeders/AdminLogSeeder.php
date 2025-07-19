<?php

namespace Database\Seeders;

use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $editorUser = User::where('email', 'editor@example.com')->first();

        $adminLogs = [
            [
                'user_id' => $adminUser->id,
                'action' => 'create',
                'target_table' => 'programs',
                'target_id' => 1,
                'note' => 'Created new program: Tahfizh Al-Quran Program',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'user_id' => $editorUser->id,
                'action' => 'update',
                'target_table' => 'programs',
                'target_id' => 2,
                'note' => 'Updated program: Islamic Studies for Women - changed description',
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'create',
                'target_table' => 'fundraisers',
                'target_id' => 1,
                'note' => 'Created new fundraiser: Build New Islamic School Wing',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'confirm',
                'target_table' => 'donations',
                'target_id' => 1,
                'note' => 'Confirmed donation: Monthly Student Sponsorship - Amount: 100,000',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $editorUser->id,
                'action' => 'create',
                'target_table' => 'activities',
                'target_id' => 2,
                'note' => 'Created new activity: Quran Recitation Competition',
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'update',
                'target_table' => 'fundraisers',
                'target_id' => 3,
                'note' => 'Updated fundraiser status: Masjid Renovation Project - marked as completed',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'reject',
                'target_table' => 'donations',
                'target_id' => 8,
                'note' => 'Rejected donation: Teacher Training Support - Payment verification failed',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $editorUser->id,
                'action' => 'publish',
                'target_table' => 'activities',
                'target_id' => 8,
                'note' => 'Published activity: New Muslim Welcome Ceremony',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'login',
                'target_table' => 'users',
                'target_id' => $adminUser->id,
                'note' => 'Admin user logged into the system',
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'bulk_update',
                'target_table' => 'donation_packages',
                'target_id' => null,
                'note' => 'Bulk updated donation packages - activated 6 packages',
                'created_at' => Carbon::now()->subHours(1),
            ],
        ];

        foreach ($adminLogs as $log) {
            AdminLog::create($log);
        }
    }
}
