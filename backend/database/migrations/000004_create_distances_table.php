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
        Schema::create('distances', function (Blueprint $table) {
            $table->id();
            $table->string('network')->nullable();

            // keep the short codes for easier debugging and portability
            $table->string('parent_short_name');
            $table->string('child_short_name');

            // resolved FK references to stations.id (nullable in case station is missing)
            $table->unsignedBigInteger('parent_station_id')->nullable();
            $table->unsignedBigInteger('child_station_id')->nullable();

            $table->decimal('distance', 8, 3);
            $table->timestamps();

            $table->foreign('parent_station_id')->references('id')->on('stations')->onDelete('set null');
            $table->foreign('child_station_id')->references('id')->on('stations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distances');
    }
};
