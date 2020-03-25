<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_method');
        });

        DB::table('default_payment_methods')->insert(array(
            array('payment_method' => 'Gotówka'),
            array('payment_method' => 'Karta debetowa'),
            array('payment_method' => 'Karta kredytowa'),
           
        ));
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_payment_methods');
    }
}
