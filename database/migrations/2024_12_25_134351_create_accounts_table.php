<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('holderName')->nullable();
            $table->string('accountNumber')->nullable();
            $table->bigInteger('icon');
            $table->bigInteger('color');
            $table->boolean('isDefault')->default(false);
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
