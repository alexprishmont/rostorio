<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->time('shifts_begins_at')
                ->default('8:00')
                ->nullable()
                ->after('name');
            $table->time('shifts_ends_at')
                ->default('17:00')
                ->nullable()
                ->after('shifts_begins_at');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('shifts_begins_at');
            $table->dropColumn('shifts_ends_at');
        });
    }
};
