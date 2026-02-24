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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('approval_flow_id');
            $table->foreign('approval_flow_id')->references('id')->on('approval_flows')->onDelete('cascade');
            $table->integer('current_step_order')->default(1);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('approvals');
    }
};
