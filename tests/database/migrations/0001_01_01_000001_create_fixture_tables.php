<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixture_int_parents', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('fixture_ulid_parents', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->timestamps();
        });

        Schema::create('fixture_uuid_parents', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->timestamps();
        });

        // The morph id is a string for every parent key type.
        Schema::create('fixture_notes', function (Blueprint $table): void {
            $table->id();
            $table->string('notable_type');
            $table->string('notable_id', 36);
            $table->string('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixture_notes');
        Schema::dropIfExists('fixture_uuid_parents');
        Schema::dropIfExists('fixture_ulid_parents');
        Schema::dropIfExists('fixture_int_parents');
    }
};
