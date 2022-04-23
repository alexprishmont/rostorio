<?php

declare(strict_types=1);

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->foreignIdFor(Company::class)
                ->after('user_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropForeignIdFor(Company::class);
        });
    }
};
