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
        Schema::create('tb_subscription', function (Blueprint $table) {
            $table->id();
            $table->date('subs_start');
            $table->date('subs_end');
            $table->string('registered_by', 3);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('tb_user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_subscription');
    }
};
