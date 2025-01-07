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
        Schema::create('restreams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('rtmp_address')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('pid')->nullable();
            $table->nestedSet(); // Kalnoy nestedset
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restreams');
    }
};
