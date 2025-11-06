<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id(); // id de la release
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->date('release_date');
            $table->string('color')->nullable();
            $table->timestamps();

            // Clé étrangère corrigée
            $table->foreign('project_id')
                  ->references('id_project') // <-- correspond à ta PK dans projects
                  ->on('projects')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
