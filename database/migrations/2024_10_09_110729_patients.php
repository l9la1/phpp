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
            $table->text('name');
            $table->text('address');
            $table->text('phonenumber');
            $table->date('date_of_birth');
            $table->boolean('approval_state')->default(false);
            $table->integer('assigned_room_id')->nullable();
            $table->date('registration_date')->useCurrent();
            $table->boolean("dead")->default(0);
            $table->timestamps();
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
