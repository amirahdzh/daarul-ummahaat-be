<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $editorUser = User::where('email', 'editor@example.com')->first();

        $programs = [
            [
                'title' => 'Tahfizh Al-Quran Program',
                'description' => 'Comprehensive Quran memorization program for children and adults with experienced teachers and structured curriculum.',
                'slug' => 'tahfizh-al-quran-program',
                'external_link' => 'https://example.com/tahfizh-program',
                'is_published' => true,
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Islamic Studies for Women',
                'description' => 'Specialized Islamic education program focusing on women\'s studies in Islam, covering fiqh, hadith, and Islamic history.',
                'slug' => 'islamic-studies-for-women',
                'external_link' => 'https://example.com/women-studies',
                'is_published' => true,
                'created_by' => $editorUser->id,
            ],
            [
                'title' => 'Arabic Language Course',
                'description' => 'Learn classical and modern Arabic language with native speakers. Suitable for beginners to advanced levels.',
                'slug' => 'arabic-language-course',
                'external_link' => 'https://example.com/arabic-course',
                'is_published' => true,
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Family Counseling Services',
                'description' => 'Islamic-based family counseling and guidance services for married couples and families.',
                'slug' => 'family-counseling-services',
                'external_link' => 'https://example.com/counseling',
                'is_published' => false,
                'created_by' => $editorUser->id,
            ],
            [
                'title' => 'Community Outreach Program',
                'description' => 'Reaching out to the community through various social activities and Islamic dawah programs.',
                'slug' => 'community-outreach-program',
                'external_link' => 'https://example.com/outreach',
                'is_published' => true,
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
