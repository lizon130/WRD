<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Event name
            $table->text('details'); // Event details
            $table->string('company')->nullable(); // Associated company, nullable
            $table->date('start_date'); // Event start date
            $table->date('end_date'); // Event end date
            $table->tinyInteger('status')->default(0); // Status (0: inactive, 1: active), default is 0
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
        Schema::dropIfExists('events');
    }
}
