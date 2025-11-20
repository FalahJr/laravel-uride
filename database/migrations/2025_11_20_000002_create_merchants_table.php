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
        Schema::create('merchant', function (Blueprint $table) {
            $table->bigIncrements('id');

            // user relation - users.id uses $table->id() (bigint)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // kategori relation
            $table->unsignedInteger('merchant_kategori_id')->nullable();
            $table->foreign('merchant_kategori_id')->references('id')->on('merchant_kategori')->onDelete('set null');

            $table->string('name', 100);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->longText('alasan_penolakan')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['merchant_kategori_id']);
        });

        Schema::dropIfExists('merchant');
    }
};
