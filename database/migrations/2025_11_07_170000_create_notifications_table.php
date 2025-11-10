<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notification');
            $table->unsignedBigInteger('user_id'); // destinataire
            $table->string('type'); // ex: 'deadline', 'update', 'mail'
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('title');
            $table->text('body')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('id_task')->on('tasks')->onDelete('set null');
            $table->foreign('project_id')->references('id_project')->on('projects')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
