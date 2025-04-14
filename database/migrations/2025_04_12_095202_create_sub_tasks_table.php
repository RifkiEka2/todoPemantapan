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
        Schema::create('sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id'); // Relasi ke tabel tasks
            $table->string('title');
            $table->enum('status', ['on_progress', 'done'])->default('on_progress'); // status default "belum"
            $table->enum('priority', ['rendah', 'sedang', 'tinggi']); // prioritas default "rendah"
            $table->date('deadline')->nullable(); // optional tenggat waktu
            $table->timestamps();
    
            // Foreign key constraint
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_tasks');
    }
};
