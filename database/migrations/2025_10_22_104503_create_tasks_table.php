<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('id_task');
            $table->unsignedBigInteger('sprint_id')->nullable();
            $table->unsignedBigInteger('epic_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'done'])->default('todo');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->foreign('sprint_id')
                ->references('id_sprint')
                ->on('sprints')
                ->onDelete('set null');

            $table->foreign('epic_id')
                ->references('id_epic')
                ->on('epics')
                ->onDelete('cascade');

            $table->foreign('assigned_to')
                ->references('id_user')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
