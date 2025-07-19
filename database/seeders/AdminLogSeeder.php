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
                'model_type' => 'Program',
                'model_id' => 1,
                'description' => 'Created new program: Tahfizh Al-Quran Program',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'user_id' => $editorUser->id,
                'action' => 'update',
                'model_type' => 'Program',
                'model_id' => 2,
                'description' => 'Updated program: Islamic Studies for Women - changed description',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'create',
                'model_type' => 'Fundraiser',
                'model_id' => 1,
                'description' => 'Created new fundraiser: Build New Islamic School Wing',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'confirm',
                'model_type' => 'Donation',
                'model_id' => 1,
                'description' => 'Confirmed donation: Monthly Student Sponsorship - Amount: 100,000',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $editorUser->id,
                'action' => 'create',
                'model_type' => 'Activity',
                'model_id' => 2,
                'description' => 'Created new activity: Quran Recitation Competition',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'update',
                'model_type' => 'Fundraiser',
                'model_id' => 3,
                'description' => 'Updated fundraiser status: Masjid Renovation Project - marked as completed',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'reject',
                'model_type' => 'Donation',
                'model_id' => 8,
                'description' => 'Rejected donation: Teacher Training Support - Payment verification failed',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $editorUser->id,
                'action' => 'publish',
                'model_type' => 'Activity',
                'model_id' => 8,
                'description' => 'Published activity: New Muslim Welcome Ceremony',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'login',
                'model_type' => null,
                'model_id' => null,
                'description' => 'Admin user logged into the system',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $adminUser->id,
                'action' => 'bulk_update',
                'model_type' => 'DonationPackage',
                'model_id' => null,
                'description' => 'Bulk updated donation packages - activated 6 packages',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subHours(1),
            ],
        ];

        foreach ($adminLogs as $log) {
            AdminLog::create($log);
        }
    }
}
