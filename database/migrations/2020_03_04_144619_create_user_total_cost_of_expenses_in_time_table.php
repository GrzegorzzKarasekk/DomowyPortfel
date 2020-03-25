<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTotalCostOfExpensesInTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_total_cost_of_expenses_in_time', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_name_id');
            $table->string('category_name');
            $table->float('amount', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_total_cost_of_expenses_in_time');
    }
}
