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

            $table->primary(['project_id', 'user_id']);

            // FK vers projects
            $table->foreign('project_id')
                  ->references('id') // ta table projects doit avoir 'id' comme clé primaire
                  ->on('projects')
                  ->onDelete('cascade');

            // FK vers users
            $table->foreign('user_id')
                  ->references('id_user') // <-- ici on pointe sur id_user
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_project');
    }
};
