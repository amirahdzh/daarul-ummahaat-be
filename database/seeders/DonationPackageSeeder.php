<?php

namespace Database\Seeders;

use App\Models\DonationPackage;
use Illuminate\Database\Seeder;

class DonationPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'title' => 'Sponsor a Student',
                'description' => 'Support a student\'s Islamic education for one month including books and materials.',
                'amount' => 100000, // 100,000 (in smallest currency unit)
                'category' => 'education',
                'is_active' => true,
            ],
            [
                'title' => 'Iftar for the Needy',
                'description' => 'Provide iftar meals for underprivileged families during Ramadan.',
                'amount' => 50000, // 50,000
                'category' => 'charity',
                'is_active' => true,
            ],
            [
                'title' => 'Quran Distribution',
                'description' => 'Sponsor the printing and distribution of Quran copies to new Muslims and communities.',
                'amount' => 25000, // 25,000
                'category' => 'dawah',
                'is_active' => true,
            ],
            [
                'title' => 'Masjid Maintenance',
                'description' => 'Contribute to the monthly maintenance and utilities of the masjid.',
                'amount' => 200000, // 200,000
                'category' => 'facility',
                'is_active' => true,
            ],
            [
                'title' => 'Orphan Care Package',
                'description' => 'Monthly care package for orphaned children including food, clothing, and educational supplies.',
                'amount' => 150000, // 150,000
                'category' => 'charity',
                'is_active' => true,
            ],
            [
                'title' => 'Teacher Training Fund',
                'description' => 'Support the training and development of Islamic education teachers.',
                'amount' => 300000, // 300,000
                'category' => 'education',
                'is_active' => true,
            ],
            [
                'title' => 'Emergency Relief Fund',
                'description' => 'Emergency assistance for families facing financial hardship.',
                'amount' => 75000, // 75,000
                'category' => 'emergency',
                'is_active' => false, // Currently inactive
            ],
        ];

        foreach ($packages as $package) {
            DonationPackage::create($package);
        }
    }
}
