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
        Schema::create('restaurant_schedules', function (Blueprint $table) {
            $table->id();
            $table->uuid('restaurant_id'); // Use UUID for foreign key
            $table->string('day_of_week', 10);
            $table->time('open_time');
            $table->time('close_time');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_schedules');
    }
};
