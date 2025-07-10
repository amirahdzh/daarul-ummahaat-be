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
        Schema::create('donation_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Nama paket, misal: "Infaq Rutin", "Beasiswa Santri"');
            $table->text('description');
            $table->bigInteger('amount')->comment('Nominal donasi yang disarankan, bisa 0 jika fleksibel');
            $table->string('category')->comment('Kategori umum, misal: infaq, zakat, sosial');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_packages');
    }
};
