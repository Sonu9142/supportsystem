<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User submitting the ticket
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low','medium','high'])->default('medium');
            $table->enum('status', ['open','in_progress','on_hold','resolved','closed'])->default('open');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable(); // Agent/admin
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
