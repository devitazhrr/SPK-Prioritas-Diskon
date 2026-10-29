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
        Schema::create('penilaians', function (Blueprint $table) {
        $table->id();

        $table->foreignId('alternative_id')->constrained()->cascadeOnDelete();
        $table->foreignId('criteria_id')->constrained()->cascadeOnDelete();

        $table->double('nilai'); // xij

        $table->timestamps();

        // biar tidak duplicate (1 alternatif 1 kriteria cuma 1 nilai)
        $table->unique(['alternative_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
