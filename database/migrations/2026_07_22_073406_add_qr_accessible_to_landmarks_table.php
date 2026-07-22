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
        Schema::table('landmarks', function (Blueprint $table) {
            $table->boolean('qr_accessible')->default(false)->after('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->dropColumn('qr_accessible');
        });
    }
};
