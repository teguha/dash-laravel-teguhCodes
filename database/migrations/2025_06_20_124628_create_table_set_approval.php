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
        Schema::create('set_approvals', function (Blueprint $table) {
            //
            $table->id();
            $table->enum('approval_by',['role','structure'])->nullable();
            $table->foreignId('role_id')->constrained('set_role')->onDelete('cascade');
            $table->foreignId('struct_id')->constrained('set_structure')->onDelete('cascade');
            $table->foreignId('sub_corp_id')->constrained('set_structure')->onDelete('cascade');
            $table->enum('approval_type', ['sequential','paralel'])->nullable();
            $table->foreignId('permission_menu_id')->constrained('set_permission')->onDelete('cascade');
            $table->integer('approval_position')->nullable();
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
        Schema::dropIfExists('set_approvals');
    }
};
