<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTotalCostOfExpensesInThisMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_total_cost_of_expenses_in_this_mounth', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_name_id');
            $table->string('category_name');
            $table->float('amount', 8, 2);
            $table->float('limit', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_total_cost_of_expenses_in_this__month');
    }
}
