<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePharmacies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pharmacies');
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->float('latitude',12,8);
            $table->float('longitude',12,8);
            $table->timestamp('created_at')->default(DB::raw('current_timestamp'));
            $table->timestamp('updated_at')->default(DB::raw('current_timestamp ON UPDATE CURRENT_TIMESTAMP'));
           
        });
        //POINT datatype is available in MySQL 5.7 with GeoSpatial features
         DB::statement("ALTER TABLE pharmacies ADD `point` POINT NOT NULL AFTER longitude" );
         DB::statement("ALTER TABLE pharmacies ADD SPATIAL INDEX(`point`)" );
         
    /**
     * CREATE distance function to calculate distance from origin to the POINT column we populated on import.
     *
     * @return void
     */
         DB::unprepared("DROP FUNCTION IF EXISTS distance;
         CREATE FUNCTION `distance`(a POINT, b POINT) RETURNS double
            DETERMINISTIC
         BEGIN
         RETURN
         round(glength(linestringfromwkb(linestring(asbinary(a),asbinary(b)))));
         END ;" );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacies');
    }
}
