<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            if (!Schema::hasColumn('sprints', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('sprints', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('sprints', 'project_id')) {
                $table->foreignId('project_id')->constrained('projects', 'id_project')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn(['start_date','end_date','project_id']);
        });
    }
};
