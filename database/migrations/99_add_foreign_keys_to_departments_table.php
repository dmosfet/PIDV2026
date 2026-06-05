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
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign(['manager_id'], 'fk_department_employees')->references(['employee_id'])->on('employees')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['department_type_id'], 'fk_department_department_types')->references(['department_type_id'])->on('department_types')->onUpdate('cascade')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign('fk_department_employees');
            $table->dropForeign('fk_department_department_types');
        });
    }
};
