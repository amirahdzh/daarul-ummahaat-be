<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create directories for images
        $directories = [
            'programs',
            'fundraisers',
            'activities',
            'donation_proofs'
        ];

        foreach ($directories as $directory) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Create placeholder images (1x1 transparent pixel)
        $placeholderImageContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

        $imageFiles = [
            // Program images
            'programs/tahfizh-program.jpg',
            'programs/women-studies.jpg',
            'programs/arabic-course.jpg',
            'programs/counseling.jpg',
            'programs/outreach.jpg',

            // Fundraiser images
            'fundraisers/school-wing.jpg',
            'fundraisers/ramadan-food.jpg',
            'fundraisers/masjid-renovation.jpg',
            'fundraisers/library-expansion.jpg',
            'fundraisers/orphan-support.jpg',
            'fundraisers/womens-center.jpg',

            // Activity images
            'activities/study-circle.jpg',
            'activities/quran-competition.jpg',
            'activities/iftar-gathering.jpg',
            'activities/parenting-workshop.jpg',
            'activities/charity-drive.jpg',
            'activities/calligraphy-class.jpg',
            'activities/eid-preparation.jpg',
            'activities/welcome-ceremony.jpg',
        ];

        foreach ($imageFiles as $imagePath) {
            Storage::disk('public')->put($imagePath, $placeholderImageContent);
        }

        $this->command->info('Created placeholder images for seeding');
    }
}
