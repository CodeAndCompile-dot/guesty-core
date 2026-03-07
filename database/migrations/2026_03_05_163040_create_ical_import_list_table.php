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
        Schema::create('ical_import_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('ical_link');
            $table->longText('property_id');
            $table->timestamps();
        });

        // Create indexes with prefix length for TEXT columns (MySQL only)
        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement('CREATE INDEX ical_import_list_ical_link_index ON ical_import_list (ical_link(255))');
            \Illuminate\Support\Facades\DB::statement('CREATE INDEX ical_import_list_property_id_index ON ical_import_list (property_id(255))');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ical_import_list');
    }
};
