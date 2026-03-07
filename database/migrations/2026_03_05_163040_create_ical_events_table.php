<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ical_events', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ppp_id');
            $table->string('ical_link')->index('ical_events_ical_link_index');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('text');
            $table->integer('event_pid')->index('ical_events_event_pid_index');
            $table->integer('cat_id');
            $table->string('uid');
            $table->string('event_type', 30);
            $table->integer('booking_status');
            $table->timestamps();

            $table->index(['ppp_id', 'start_date', 'end_date', 'cat_id', 'booking_status'], 'ical_events_ppp_id_composite_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ical_events');
    }
};
