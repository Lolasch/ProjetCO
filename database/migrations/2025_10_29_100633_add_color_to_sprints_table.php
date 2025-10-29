<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            if (!Schema::hasColumn('sprints', 'color')) {
                $table->string('color')->default('#1e40af');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
