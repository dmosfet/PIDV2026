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
        Schema::table('employees', function (Blueprint $table) {
            //$table->foreign(['manager_id'], 'fk_employees_employees')->references(['employee_id'])->on('employees')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['department_id'], 'fk_employees_departments')->references(['department_id'])->on('departments')->onUpdate('no action')->onDelete('no action');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //$table->dropForeign('fk_employees_employees');
            $table->dropForeign('fk_employees_departments');
        });
    }
};
