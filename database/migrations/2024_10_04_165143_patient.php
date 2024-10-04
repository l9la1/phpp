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
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            $table->string("front_name");
            $table->string("last_name");
            $table->string("family_id");
            $table->string("phone_number");
            $table->string("email");
            $table->boolean("aproved");
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
