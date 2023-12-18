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
        Schema::create('public_apis', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('api'); 
            $table->text('description')->nullable();
            $table->string('auth')->nullable();
            $table->boolean('https')->nullable();
            $table->string('cors')->nullable();
            $table->string('link')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_apis');
    }
};
