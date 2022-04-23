<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->string('request')->nullable();
            $table->boolean('is_editable')->default(true);
            $table->date('shift_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_requests');
    }
};
