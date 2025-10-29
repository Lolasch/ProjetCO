<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('epics', function (Blueprint $table) {
            if (!Schema::hasColumn('epics', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('epics', 'sprint_id')) {
                $table->foreignId('sprint_id')->constrained('sprints', 'id_sprint')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('epics', function (Blueprint $table) {
            $table->dropForeign(['sprint_id']);
            $table->dropColumn(['description','sprint_id']);
        });
    }
};
