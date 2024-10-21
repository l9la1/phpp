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
            $table->foreign('login_id')->references('login_id')->on('users')->onDelete('cascade');
            $table->text('name');
            $table->text('address');
            $table->text(column: 'phonenumber');
            $table->date('date_of_birth');
            $table->boolean('approval_state');
            $table->integer('assigned_room_id');
            $table->date('registration_date');
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
