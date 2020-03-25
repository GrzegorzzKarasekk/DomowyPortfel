<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name'); 
        });

        DB::table('default_incomes')->insert(array(
            array('category_name' => 'Wpłata'),
            array('category_name' => 'Odsetki Bankowe'),
            array('category_name' => 'Allegro'),
            array('category_name' => 'Inne'),
        ));
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_incomes');
    }
}
