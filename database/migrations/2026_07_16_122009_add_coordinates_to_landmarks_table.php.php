<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->decimal('latitude', 9, 6)->nullable()->after('pos_y');
            $table->decimal('longitude', 9, 6)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
