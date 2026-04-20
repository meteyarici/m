<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 1) auctions tablosuna AuctionController@store ve Redis hot-state tarafından
     *    kullanılan fakat ilk migration'da unutulan kolonları ekler.
     * 2) Atomik bid + winner hesaplama için bids tablosunu oluşturur.
     *
     * Bu migration additive'tir: mevcut auctions kayıtlarını bozmaz,
     * eski kolonları (bid_increment vb.) kaldırmaz.
     */
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            if (! Schema::hasColumn('auctions', 'title')) {
                $table->string('title', 255)->nullable()->after('product_id');
            }

            if (! Schema::hasColumn('auctions', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (! Schema::hasColumn('auctions', 'image')) {
                $table->string('image', 500)->nullable()->after('description');
            }

            if (! Schema::hasColumn('auctions', 'start_price')) {
                $table->decimal('start_price', 12, 4)->default(0)->after('end_at');
            }

            if (! Schema::hasColumn('auctions', 'current_price')) {
                $table->decimal('current_price', 12, 4)->default(0)->after('start_price');
            }

            if (! Schema::hasColumn('auctions', 'min_increment')) {
                $table->decimal('min_increment', 12, 4)->default(1)->after('bid_increment');
            }

            if (! Schema::hasColumn('auctions', 'winner_customer_id')) {
                $table->unsignedInteger('winner_customer_id')->nullable()->after('status');
            }

            if (! Schema::hasColumn('auctions', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('winner_customer_id');
            }
        });

        if (! Schema::hasTable('bids')) {
            Schema::create('bids', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('auction_id');
                $table->unsignedInteger('customer_id');
                $table->decimal('amount', 12, 4);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();

                $table->index(['auction_id', 'amount']);
                $table->index(['auction_id', 'created_at']);
                $table->index(['customer_id', 'created_at']);

                $table->foreign('auction_id')
                    ->references('id')->on('auctions')
                    ->onDelete('cascade');

                $table->foreign('customer_id')
                    ->references('id')->on('customers')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');

        Schema::table('auctions', function (Blueprint $table) {
            foreach (['closed_at', 'winner_customer_id', 'min_increment', 'current_price', 'start_price', 'image', 'description', 'title'] as $column) {
                if (Schema::hasColumn('auctions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
