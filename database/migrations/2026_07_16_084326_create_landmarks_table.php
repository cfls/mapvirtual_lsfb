<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landmarks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('region')->nullable(); // e.g. Bruxelles, Wallonie, Flandre
            $table->string('excerpt')->nullable(); // short teaser under the pin
            $table->text('description')->nullable(); // longer text next to the video
            $table->string('image_url')->nullable(); // pin thumbnail / card cover
            $table->string('cloudinary_public_id')->nullable(); // e.g. "lsfb/grand-place"
            $table->string('cloudinary_cloud_name')->nullable(); // your Cloudinary cloud name
            $table->decimal('pos_x', 5, 2); // % from left of the map canvas (0-100)
            $table->decimal('pos_y', 5, 2); // % from top of the map canvas (0-100)
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landmarks');
    }
};
