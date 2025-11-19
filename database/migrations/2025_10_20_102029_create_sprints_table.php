<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('sprints', function (Blueprint $table) {
        $table->id('id_sprint');
        $table->unsignedBigInteger('project_id'); 
        $table->string('name');
        $table->date('start_date');
        $table->date('end_date');
        $table->timestamps();

        $table->foreign('project_id')->references('id_project')->on('projects')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprints');
    }
};
