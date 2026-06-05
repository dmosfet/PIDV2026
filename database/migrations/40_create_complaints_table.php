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
        Schema::create('complaints', function (Blueprint $table) {
            // Clef primaire
            $table->id('complaint_id');

            // Champs d'identification de la plainte
            $table->string('complaint_reference')->unique()->nullable();
            $table->date('complaint_date');
            $table->date('reception_date');
            $table->text('object')->nullable();

            // Relations obligatoires basées sur des Enums
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('complaint_type_id');
            $table->unsignedBigInteger('object_category_id')->nullable();
            $table->unsignedBigInteger('status')->default(\App\Enums\ComplaintStatus::NEW->value);

            // Relations non obligatoires à la création
            $table->unsignedBigInteger('complainant_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('profession_id')->nullable();

            // Autres Champs nullables
            $table->date('evaluation_date')->nullable();
            $table->boolean('admissible')->nullable();
            $table->boolean('well_founded')->nullable();
            $table->date('acknowledgment_date')->nullable();
            $table->date('transmission_date')->nullable();
            $table->text('response')->nullable();
            $table->date('response_date')->nullable();
            $table->integer('duration')->nullable();

            // Champs liés au recours
            $table->unsignedBigInteger('appealed_by_id')->nullable();
            $table->unsignedBigInteger('appeal_about_id')->nullable();

            // Champs d'historisation
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->unsignedBigInteger('created_by_user');
            $table->unsignedBigInteger('created_by_department')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
