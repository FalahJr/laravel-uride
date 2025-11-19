<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_driver', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Driver identity
            $table->string('nomor_plat', 20)->unique();
            $table->string('nomor_sim', 50)->unique();
            $table->string('nomor_stnk', 50)->unique();
            $table->string('nomor_ktp', 50)->unique();
            $table->longText('alamat');

            // Additional helpful attributes


            $table->float('reputasi')->default(0);
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending', 'rejected'])->default('inactive');
            $table->longText('alasan_penolakan')->nullable();

            $table->timestamps();

            // Indexes for faster lookups
            $table->index('nomor_plat');
            $table->index('nomor_sim');
            $table->index('nomor_ktp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_driver');
    }
};
