<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_project', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role'); // manager, employee, client

            $table->timestamps();

            // Clé primaire composite
            $table->primary(['project_id', 'user_id']);

            // Clés étrangères cohérentes
            $table->foreign('project_id')
                  ->references('id_project')->on('projects')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('user_id')
                  ->references('id_user')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_project');
    }
};
