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
        Schema::create('perencanaans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('no')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('date')->nullable();
            $table->unsignedBigInteger('structure_id');
            $table->unsignedBigInteger('approval_id');
            $table->foreign('structure_id')->references('id')->on('set_structure')->onDelete('cascade');
            $table->foreign('approval_id')->references('id')->on('approvals')->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
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
        Schema::dropIfExists('perencanaan');
    }
};
