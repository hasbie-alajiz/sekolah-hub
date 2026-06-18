<?php

declare(strict_types=1);

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
        Schema::create('gallery_album_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('album_id');
            $table->unsignedBigInteger('media_id'); // cross-module, no FK
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->nullable();

            $table->foreign('album_id')
                ->references('id')
                ->on('gallery_albums')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_album_items');
    }
};
