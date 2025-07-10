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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->string('image');
            $table->string('external_link');
            $table->boolean('is_published')->default(false)->comment('Kontrol apakah konten ini ditampilkan publik');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete, nullable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
