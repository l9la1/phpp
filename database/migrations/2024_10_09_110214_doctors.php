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
            $table->id();  // Primary key
            $table->unsignedBigInteger('login_id'); 
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('contact_email');  
            $table->string('contact_phone');  
            $table->string('specialty'); 
            $table->timestamps();
        
            // Set up foreign key constraint to the users table
            $table->foreign('login_id')->references('id')->on('users')->onDelete('cascade');
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
