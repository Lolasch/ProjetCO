<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->string('image')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn(['image', 'start_date', 'end_date']);
    });
}

};
