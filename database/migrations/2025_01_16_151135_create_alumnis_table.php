<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Alumni name
            $table->string('email')->unique(); // Alumni email, must be unique
            $table->string('phone_no')->nullable(); // Phone number, nullable
            $table->year('batch'); // Batch year
            $table->year('passing_year'); // Passing year
            $table->string('current_profession')->nullable(); // Current profession, nullable
            $table->string('company_name')->nullable(); // Company name, nullable
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnis');
    }
}
