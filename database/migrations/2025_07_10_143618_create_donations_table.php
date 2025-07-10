<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('User yang login (opsional)');
            $table->foreignId('donation_package_id')->nullable()->constrained('donation_packages')->comment('Opsional');
            $table->foreignId('fundraiser_id')->nullable()->constrained('fundraisers')->comment('Opsional');
            $table->string('title')->comment('Judul donasi jika tidak terkait campaign atau paket');
            $table->string('name');
            $table->string('email')->nullable()->comment('Email pendonor (opsional, jika tidak login)');
            $table->string('phone');
            $table->string('category');
            $table->bigInteger('amount');
            $table->string('status')->comment('pending | confirmed | cancelled');
            $table->string('proof_image')->nullable()->comment('Opsional, untuk bukti transfer');
            $table->text('confirmation_note')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
