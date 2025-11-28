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
        Schema::create('shipping_notes', function (Blueprint $table) {
            $table->id();
            $table->string('ship_no')->unique();
            $table->string('ship_type')->nullable();
            $table->string('planner')->nullable();
            $table->string('route_name')->nullable();
            $table->dateTime('arrive_time')->nullable();
            $table->dateTime('depart_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_notes');
    }
};
