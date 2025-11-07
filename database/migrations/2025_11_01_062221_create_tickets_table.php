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
            $table->string('ticket_id',60)->unique();
            $table->enum('issue_category', ['transactional', 'non-transactional']);
            $table->string('services',60);
            $table->string('title',60);
            $table->text('description');
            $table->string('transaction_id')->nullable();
            $table->string('file_path')->nullable();

            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('assigned_at')->nullable();

            $table->enum('status', ['pending', 'acknowledged', 'resolved', 'reassigned'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
