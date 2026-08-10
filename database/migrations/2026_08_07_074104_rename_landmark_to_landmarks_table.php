<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('landmark', 'landmarks');
    }

    public function down(): void
    {
        Schema::rename('landmarks', 'landmark');
    }
};
