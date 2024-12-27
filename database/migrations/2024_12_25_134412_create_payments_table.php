<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('account')->nullable(); // Tambahkan kolom account
            $table->unsignedBigInteger('category')->nullable(); 
            $table->foreign('account')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('category')->references('id')->on('categories')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['DR', 'CR']); // Debit or Credit
            $table->timestamp('datetime');
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
