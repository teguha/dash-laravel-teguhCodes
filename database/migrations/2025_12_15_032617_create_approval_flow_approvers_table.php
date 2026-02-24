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
        Schema::create('approval_flow_approvers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approval_flow_step_id')->refrenced('id')->on('approval_flow_steps')->onDelete('cascade')->nullable();
            // $table->foreignId('approval_flow_step_id')->constrained('approval_flow_steps')->cascadeOnDelete();
            $table->enum('approver_type', ['role', 'structure', 'user']);
            $table->unsignedBigInteger('approver_value');
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
        Schema::dropIfExists('approval_flow_approvers');
    }
};
