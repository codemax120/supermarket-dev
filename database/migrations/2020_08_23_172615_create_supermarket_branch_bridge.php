<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupermarketBranchBridge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supermarket_branch_bridge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supermarket_id');
            $table->unsignedBigInteger('supermarket_branch_id');

            $table->foreign('supermarket_id')->references('id')->on('supermarket');
            $table->foreign('supermarket_branch_id')->references('id')->on('supermarket_branch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supermarket_branch_bridge');
    }
}
