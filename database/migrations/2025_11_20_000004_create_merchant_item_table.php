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
        Schema::create('merchant_item', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('merchant')->onDelete('cascade');

            $table->string('nama_barang', 100);
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('image_url', 255)->nullable();
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant_item', function (Blueprint $table) {
            $table->dropForeign(['merchant_id']);
        });

        Schema::dropIfExists('merchant_item');
    }
};
