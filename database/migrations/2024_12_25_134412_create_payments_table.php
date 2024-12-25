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
            $table->foreignId('account')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('category')->constrained('categories')->onDelete('cascade');
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
