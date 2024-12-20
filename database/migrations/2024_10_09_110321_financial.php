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
        Schema::create('financial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->double('hire_cost',10,2);
            $table->double('caretaking_costs',10,2);
            $table->boolean('payed')->default(0);

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
