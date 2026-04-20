<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();


            $table->unsignedInteger('user_id');


            $table->unsignedInteger('product_id');


            $table->timestamp('start_at');
            $table->timestamp('end_at');


            $table->decimal('min_price', 12, 4)->default(0.0000);
            $table->decimal('buy_now_price', 12, 4)->default(0.0000)->nullable();
            $table->decimal('bid_increment', 12, 4)->default(0.0000);


            $table->boolean('is_buy_now_available')->default(false);


            $table->string('status')->default('pending')->comment("ENUM: pending, approved, started, paused, rejected, cancelled");;

            $table->timestamps();


            $table->foreign('user_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};




