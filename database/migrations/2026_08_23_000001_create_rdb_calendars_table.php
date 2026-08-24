<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRdbCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('rdb_calendars')) {
            return; // table already created manually
        }

        Schema::create('rdb_calendars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('calendar_date');
            $table->string('section_type', 50); // 'receive' | 'delivery'
            $table->boolean('is_working_day')->default(true);
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['calendar_date', 'section_type']);
            $table->index(['calendar_date', 'section_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rdb_calendars');
    }
}
