<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->integer('installments');
            $table->decimal('monthly_deduction', 10, 2);
            $table->text('description')->nullable();
            $table->string('start_month');
            $table->string('end_month');
            $table->enum('contract_status', ['ساري', 'مكتمل', 'ملغي']);
            $table->string('cancel_reason')->nullable();
            $table->text('notes')->nullable();
            $table->integer('months_count');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
