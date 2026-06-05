<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->string('name', 100)->unique('name');
            $table->string('code', 10)->unique('code');
            $table->string('address', 50);
            $table->string('city', 50);
            $table->string('zip_code', 4);
            $table->unsignedBigInteger('manager_id')->nullable()->index('manager_id');
            $table->unsignedBigInteger('department_type_id')->index('department_type_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
