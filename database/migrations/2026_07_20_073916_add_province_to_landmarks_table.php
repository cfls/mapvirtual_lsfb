<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            // Belgium's 10 provinces, plus "Bruxelles-Capitale" which is
            // not a province but is included here as its own filter value
            // since that's how users think of it geographically.
            $table->string('province')->nullable()->after('region');
        });
    }

    public function down(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->dropColumn('province');
        });
    }
};