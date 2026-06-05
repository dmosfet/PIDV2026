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
        Schema::table('complaints', function (Blueprint $table) {
            // Liées à une Enum dans l'application
            $table->foreign(['channel_id'], 'fk_complaints_channels')->references(['channel_id'])->on('channels')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['complaint_type_id'], 'fk_complaints_complaint_types')->references('complaint_type_id')->on('complaint_types')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['object_category_id'], 'fk_complaints_object_categories')->references(['object_category_id'])->on('object_categories')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['status'], 'fk_complaints_complaint_status')->references(['complaint_status_id'])->on('complaint_status')->onUpdate('cascade')->onDelete('no action');

            // Contraintes tables métiers
            $table->foreign(['profession_id'], 'fk_complaints_professions')->references(['profession_id'])->on('professions')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['department_id'], 'fk_complaints_departments')->references(['department_id'])->on('departments')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['customer_id'], 'fk_complaints_customers')->references(['customer_id'])->on('customers')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['employee_id'], 'fk_complaints_employees')->references(['employee_id'])->on('employees')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['complainant_id'], 'fk_complaints_complainants')->references(['complainant_id'])->on('complainants')->onUpdate('cascade')->onDelete('no action');

            // Contraintes liés au recours
            $table->foreign(['appealed_by_id'], 'fk_complaints_appealed_by')->references(['complaint_id'])->on('complaints')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['appeal_about_id'], 'fk_complaints_appeal_about')->references(['complaint_id'])->on('complaints')->onUpdate('cascade')->onDelete('no action');

            // Historisation de la création
            $table->foreign(['created_by_user'], 'fk_complaints_users_creators')->references(['user_id'])->on('users')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['created_by_department'], 'fk_complaints_departments_creators')->references(['department_id'])->on('departments')->onUpdate('cascade')->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {

            // Liées à une Enum dans l'application
            $table->dropForeign('fk_complaints_channels');
            $table->dropForeign('fk_complaints_complaint_types');
            $table->dropForeign('fk_complaints_object_categories');
            $table->dropForeign('fk_complaints_complaint_status');

            // Contraintes tables métiers
            $table->dropForeign('fk_complaints_professions');
            $table->dropForeign('fk_complaints_departments');
            $table->dropForeign('fk_complaints_customers');
            $table->dropForeign('fk_complaints_employees');
            $table->dropForeign('fk_complaints_complainants');

            // Historisation de la création
            $table->dropForeign('fk_complaints_users_creators');
            $table->dropForeign('fk_complaints_departments_creators');
        });
    }
};
