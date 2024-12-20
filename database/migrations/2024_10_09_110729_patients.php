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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('login_id');
            $table->string('name');
            $table->string('address');
            $table->string('phonenumber');
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->tinyInteger('approval_state')->default(0);
            $table->unsignedBigInteger('assigned_room_id')->nullable();
            $table->date('registration_date');
            $table->boolean('dead')->default(false);
            $table->timestamps();

            $table->foreign('login_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('patients');
    }
};
