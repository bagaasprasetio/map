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
        Schema::create('tb_transaction', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->integer('transaction_total');
            $table->string('nik_type', 2);
            $table->string('transaction_status', 2);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pangkalan_id');
            $table->foreign('user_id')->references('id')->on('tb_user')->onDelete('cascade');
            $table->foreign('pangkalan_id')->references('id')->on('tb_pangkalan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaction');
    }
};
