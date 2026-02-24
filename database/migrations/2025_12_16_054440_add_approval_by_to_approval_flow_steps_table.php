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
        Schema::table('approval_flow_steps', function (Blueprint $table) {
            //
            $table->enum('approval_by',['user', 'role', 'structure'])->default('role');
            $table->string('approver_value', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approval_flow_steps', function (Blueprint $table) {
            //
        });
    }
};
