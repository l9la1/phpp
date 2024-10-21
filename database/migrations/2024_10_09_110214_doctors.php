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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreign('login_id')->references('login_id')->on('users')->onDelete('cascade');
            $table->text('name');
            $table->date('date_of_birth');
            $table->text('contact_email');
            $table->text('contact_phone');
            $table->text('specialty');
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
