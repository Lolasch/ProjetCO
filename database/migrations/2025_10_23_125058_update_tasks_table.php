<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'status')) {
                $table->string('status')->default('À faire');
            }
            if (!Schema::hasColumn('tasks', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users', 'id_user')->onDelete('set null');
            }
            if (!Schema::hasColumn('tasks', 'due_date')) {
                $table->date('due_date')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'epic_id')) {
                $table->foreignId('epic_id')->constrained('epics', 'id_epic')->onDelete('cascade');
            }
            if (!Schema::hasColumn('tasks', 'sprint_id')) {
                $table->foreignId('sprint_id')->nullable()->constrained('sprints', 'id_sprint')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to','epic_id','sprint_id']);
            $table->dropColumn(['status','assigned_to','due_date','epic_id','sprint_id']);
        });
    }
};
