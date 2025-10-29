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
    Schema::table('sprints', function (Blueprint $table) {
        $table->unsignedBigInteger('epic_id')->nullable()->after('project_id');

        // Clé étrangère
        $table->foreign('epic_id')->references('id_epic')->on('epics')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('sprints', function (Blueprint $table) {
        $table->dropForeign(['epic_id']);
        $table->dropColumn('epic_id');
    });
}

};
