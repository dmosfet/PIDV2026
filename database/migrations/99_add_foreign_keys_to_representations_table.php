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
        Schema::table('representations', function (Blueprint $table) {
            $table->foreign(['complainant_id'], 'fk_representations_complainants')->references(['complainant_id'])->on('complainants')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['customer_id'], 'fk_representations_customers')->references(['customer_id'])->on('customers')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('representations', function (Blueprint $table) {
            $table->dropForeign('fk_representations_complainants');
            $table->dropForeign('fk_representations_customers');
        });
    }
};
