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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->mediumText('content')->nullable();
            $table->unsignedTinyInteger('rating')->default(3);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedTinyInteger('status')->default(1); // 1 = active, 0 = deleted
            $table->softDeletes();
            $table->timestamps();
            $table->index(['user_id', 'book_id']);  // Composite index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
