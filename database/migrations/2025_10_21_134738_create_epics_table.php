<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('epics', function (Blueprint $table) {
            $table->id('id_epic'); // ✅ cohérent avec les autres tables
            $table->unsignedBigInteger('project_id'); // Epic liée à un projet
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id_project')
                ->on('projects')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('epics');
    }
};
