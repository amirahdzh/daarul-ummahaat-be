<?php

namespace Database\Seeders;

use App\Models\Fundraiser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FundraiserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $editorUser = User::where('email', 'editor@example.com')->first();

        $fundraisers = [
            [
                'title' => 'Build New Islamic School Wing',
                'slug' => 'build-new-islamic-school-wing',
                'description' => 'We are raising funds to build a new wing for our Islamic school to accommodate more students and provide better facilities for quality Islamic education.',
                'target_amount' => 50000000, // 50 million
                'current_amount' => 12500000, // 12.5 million raised so far
                'deadline' => Carbon::now()->addMonths(6),
                'status' => 'active',
                'created_by' => $adminUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Ramadan Food Distribution',
                'slug' => 'ramadan-food-distribution',
                'description' => 'Help us provide iftar meals and food packages to underprivileged families during the holy month of Ramadan.',
                'target_amount' => 10000000, // 10 million
                'current_amount' => 8750000, // 8.75 million raised
                'deadline' => Carbon::now()->addDays(45),
                'status' => 'active',
                'created_by' => $editorUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Masjid Renovation Project',
                'slug' => 'masjid-renovation-project',
                'description' => 'Complete renovation of our community masjid including new prayer hall, ablution facilities, and accessibility improvements.',
                'target_amount' => 25000000, // 25 million
                'current_amount' => 25000000, // Fully funded
                'deadline' => Carbon::now()->subDays(15), // Expired but completed
                'status' => 'completed',
                'created_by' => $adminUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Islamic Library Expansion',
                'slug' => 'islamic-library-expansion',
                'description' => 'Expand our Islamic library with new books, digital resources, and reading spaces for students and community members.',
                'target_amount' => 15000000, // 15 million
                'current_amount' => 3200000, // 3.2 million raised
                'deadline' => Carbon::now()->addMonths(8),
                'status' => 'active',
                'created_by' => $editorUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Orphan Support Program',
                'slug' => 'orphan-support-program',
                'description' => 'Ongoing support program for orphaned children in our community, providing education, healthcare, and basic needs.',
                'target_amount' => 30000000, // 30 million
                'current_amount' => 18500000, // 18.5 million raised
                'deadline' => Carbon::now()->addYear(),
                'status' => 'active',
                'created_by' => $adminUser->id,
                'is_published' => true,
            ],
            [
                'title' => 'Women\'s Education Center',
                'slug' => 'womens-education-center',
                'description' => 'Establish a dedicated education center for women\'s Islamic studies and skill development programs.',
                'target_amount' => 20000000, // 20 million
                'current_amount' => 1800000, // 1.8 million raised
                'deadline' => Carbon::now()->addMonths(10),
                'status' => 'draft',
                'created_by' => $editorUser->id,
                'is_published' => false,
            ],
        ];

        foreach ($fundraisers as $fundraiser) {
            Fundraiser::create($fundraiser);
        }
    }
}
