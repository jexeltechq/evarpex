<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscrowsTable extends Migration
{
    public function up()
    {
        Schema::create('escrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained('ad_lists');
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('seller_id')->constrained('users');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'funded', 'released', 'cancelled', 'disputed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('escrows');
    }
}