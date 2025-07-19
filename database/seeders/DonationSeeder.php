<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\User;
use App\Models\DonationPackage;
use App\Models\Fundraiser;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $packages = DonationPackage::all();
        $fundraisers = Fundraiser::where('status', 'active')->get();

        $donations = [
            // Package-based donations
            [
                'user_id' => $users->where('email', 'user@example.com')->first()->id,
                'donation_package_id' => $packages->where('title', 'Sponsor a Student')->first()->id,
                'fundraiser_id' => null,
                'title' => 'Monthly Student Sponsorship',
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'phone' => '+1234567891',
                'category' => 'education',
                'amount' => 100000,
                'status' => 'confirmed',
                'confirmation_note' => 'Thank you for sponsoring student Ahmad for this month.',
                'confirmed_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => null, // Anonymous donation
                'donation_package_id' => $packages->where('title', 'Iftar for the Needy')->first()->id,
                'fundraiser_id' => null,
                'title' => 'Ramadan Iftar Support',
                'name' => 'Anonymous Donor',
                'email' => 'donor1@example.com',
                'phone' => '+1234567800',
                'category' => 'charity',
                'amount' => 50000,
                'status' => 'confirmed',
                'confirmation_note' => 'Your iftar donation will feed 10 families.',
                'confirmed_at' => Carbon::now()->subDays(3),
            ],

            // Fundraiser-based donations
            [
                'user_id' => $users->where('email', 'editor@example.com')->first()->id,
                'donation_package_id' => null,
                'fundraiser_id' => $fundraisers->where('title', 'Build New Islamic School Wing')->first()->id,
                'title' => 'Support for School Construction',
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'phone' => '+1234567892',
                'category' => 'education',
                'amount' => 500000,
                'status' => 'confirmed',
                'confirmation_note' => 'Thank you for supporting our school expansion project.',
                'confirmed_at' => Carbon::now()->subDays(7),
            ],
            [
                'user_id' => null,
                'donation_package_id' => null,
                'fundraiser_id' => $fundraisers->where('title', 'Ramadan Food Distribution')->first()->id,
                'title' => 'Ramadan Food Support',
                'name' => 'Fatimah Ahmad',
                'email' => 'fatimah@example.com',
                'phone' => '+1234567801',
                'category' => 'charity',
                'amount' => 250000,
                'status' => 'confirmed',
                'confirmation_note' => 'Barakallahu feeki for your generous contribution.',
                'confirmed_at' => Carbon::now()->subDays(2),
            ],

            // Pending donations
            [
                'user_id' => $users->where('email', 'user@example.com')->first()->id,
                'donation_package_id' => $packages->where('title', 'Quran Distribution')->first()->id,
                'fundraiser_id' => null,
                'title' => 'Quran Distribution Support',
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'phone' => '+1234567891',
                'category' => 'dawah',
                'amount' => 25000,
                'status' => 'pending',
                'confirmation_note' => null,
                'confirmed_at' => null,
            ],

            // Custom amount donations
            [
                'user_id' => null,
                'donation_package_id' => null,
                'fundraiser_id' => $fundraisers->where('title', 'Orphan Support Program')->first()->id,
                'title' => 'Custom Orphan Support',
                'name' => 'Abdullah Rahman',
                'email' => 'abdullah@example.com',
                'phone' => '+1234567802',
                'category' => 'charity',
                'amount' => 750000, // Custom amount
                'status' => 'confirmed',
                'confirmation_note' => 'Your donation will support 5 orphaned children.',
                'confirmed_at' => Carbon::now()->subDays(1),
            ],

            // More package donations
            [
                'user_id' => $users->where('email', 'admin@example.com')->first()->id,
                'donation_package_id' => $packages->where('title', 'Masjid Maintenance')->first()->id,
                'fundraiser_id' => null,
                'title' => 'Monthly Masjid Support',
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '+1234567890',
                'category' => 'facility',
                'amount' => 200000,
                'status' => 'confirmed',
                'confirmation_note' => 'JazakAllahu khair for supporting our masjid.',
                'confirmed_at' => Carbon::now()->subDays(4),
            ],

            // Rejected donation (for testing)
            [
                'user_id' => null,
                'donation_package_id' => $packages->where('title', 'Teacher Training Fund')->first()->id,
                'fundraiser_id' => null,
                'title' => 'Teacher Training Support',
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '+1234567803',
                'category' => 'education',
                'amount' => 300000,
                'status' => 'rejected',
                'confirmation_note' => 'Payment verification failed. Please try again.',
                'confirmed_at' => null,
            ],
        ];

        foreach ($donations as $donation) {
            Donation::create($donation);
        }
    }
}
