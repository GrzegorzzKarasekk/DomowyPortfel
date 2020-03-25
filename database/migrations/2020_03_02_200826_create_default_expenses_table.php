<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name');
        });

        DB::table('default_expenses')->insert(array(
            array('category_name' => 'Jedzenie'),
            array('category_name' => 'Mieszkanie'),
            array('category_name' => 'Transport'),
            array('category_name' => 'Telekomunikacja'),
            array('category_name' => 'Opieka zdrowotna'),
            array('category_name' => 'Ubranie'),
            array('category_name' => 'Higiena'),
            array('category_name' => 'Dzieci'),
            array('category_name' => 'Rozrywka'),
            array('category_name' => 'Wycieczka'),
            array('category_name' => 'Szkolenia'),
            array('category_name' => 'Książki'),
            array('category_name' => 'Oszczędności'),
            array('category_name' => 'Na złotą jesień'),
            array('category_name' => 'Spłata długów'),
            array('category_name' => 'Darowizna'),
            array('category_name' => 'Inne wydatki'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_expenses');
    }
}
