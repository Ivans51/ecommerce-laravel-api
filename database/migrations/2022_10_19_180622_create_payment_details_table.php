<?php

use App\Helpers\Enums\StatusPayment;
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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('amount');
            $table->string('provider');
            $table->enum('status', [StatusPayment::COMPLETED, StatusPayment::PENDING, StatusPayment::CANCELED]);
            $table->uuid('order_details_id');
            $table->foreign('order_details_id')->references('id')->on('order_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
};
