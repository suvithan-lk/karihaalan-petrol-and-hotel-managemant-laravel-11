<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotel_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('hotel_rooms', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('is_available');
            }
        });

        Schema::table('hotel_rooms', function (Blueprint $table) {
            $table->unique('room_number', 'hotel_rooms_room_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('hotel_rooms', function (Blueprint $table) {
            $table->dropUnique('hotel_rooms_room_number_unique');
        });

        if (Schema::hasColumn('hotel_rooms', 'is_approved')) {
            Schema::table('hotel_rooms', function (Blueprint $table) {
                $table->dropColumn('is_approved');
            });
        }
    }
};
