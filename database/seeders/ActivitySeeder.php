<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $editorUser = User::where('email', 'editor@example.com')->first();

        $activities = [
            [
                'title' => 'Weekly Islamic Study Circle',
                'description' => 'Join our weekly study circle where we discuss Quranic verses, hadith, and their practical applications in daily life. Open to all sisters.',
                'event_date' => Carbon::now()->addDays(7),
                'image' => 'activities/study-circle.jpg',
                'created_by' => $adminUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Quran Recitation Competition',
                'description' => 'Annual Quran recitation competition for children and adults. Prizes will be awarded to winners in different age categories.',
                'event_date' => Carbon::now()->addDays(21),
                'image' => 'activities/quran-competition.jpg',
                'created_by' => $editorUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Iftar Community Gathering',
                'description' => 'Community iftar during Ramadan. Everyone is welcome to join us for breaking the fast together and strengthening our bonds.',
                'event_date' => Carbon::now()->addDays(14),
                'image' => 'activities/iftar-gathering.jpg',
                'created_by' => $adminUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Islamic Parenting Workshop',
                'description' => 'Workshop for parents on raising children with Islamic values in modern society. Includes practical tips and Q&A session.',
                'event_date' => Carbon::now()->addDays(35),
                'image' => 'activities/parenting-workshop.jpg',
                'created_by' => $editorUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Charity Drive for Orphans',
                'description' => 'Community charity drive to collect clothes, toys, and educational materials for orphaned children in our care.',
                'event_date' => Carbon::now()->addDays(28),
                'image' => 'activities/charity-drive.jpg',
                'created_by' => $adminUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Arabic Calligraphy Class',
                'description' => 'Learn the beautiful art of Arabic calligraphy. Basic materials will be provided. Suitable for beginners.',
                'event_date' => Carbon::now()->addDays(42),
                'image' => 'activities/calligraphy-class.jpg',
                'created_by' => $editorUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Eid Celebration Preparation',
                'description' => 'Community preparation for Eid celebration. Help with decorations, food preparation, and organizing activities for children.',
                'event_date' => Carbon::now()->addDays(10),
                'image' => 'activities/eid-preparation.jpg',
                'created_by' => $adminUser->id,
                'is_published' => false, // Still in planning
            ],
            [
                'title' => 'New Muslim Welcome Ceremony',
                'description' => 'Special ceremony to welcome new members to our Islamic community. Introduction to Islamic practices and community support.',
                'event_date' => Carbon::now()->addDays(49),
                'image' => 'activities/welcome-ceremony.jpg',
                'created_by' => $editorUser->id,
                'is_published' => true,
            ],
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}
