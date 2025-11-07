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
        Schema::create('set_structure', function (Blueprint $table) {
            //
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->enum('level', ['main_corp', 'sub_corp', 'bagian', 'sub_bagian','sub_sub_bagian']);
            $table->string('slug',255);
            $table->string('name',255)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('address',255)->nullable();
            $table->string('tax',100)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('sys_log');
    }
};
