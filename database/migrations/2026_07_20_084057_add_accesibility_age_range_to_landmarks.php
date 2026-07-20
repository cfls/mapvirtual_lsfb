<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            // Accessibility ON SITE (QR code, interpreter/guide present),
            // not to be confused with our own LSFB video, which every
            // landmark already has.
            $table->boolean('lsfb_accessible')->default(false)->after('province');
            $table->string('age_range')->nullable()->after('lsfb_accessible');
        });
    }

    public function down(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->dropColumn(['lsfb_accessible', 'age_range']);
        });
    }
};