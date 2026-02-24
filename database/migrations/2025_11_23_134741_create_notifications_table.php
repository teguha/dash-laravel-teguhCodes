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
        Schema::create('notifications', function (Blueprint $table) {
            // $table->id();
            // $table->timestamps();
            $table->id();
            $table->morphs('notifiable'); // Menyimpan informasi tentang siapa yang menerima notifikasi (User atau model lain)
            $table->string('type'); // Jenis notifikasi (misalnya DataCreatedNotification)
            $table->text('data'); // Data terkait notifikasi (disimpan dalam bentuk JSON)
            $table->timestamp('read_at')->nullable(); // Menandai kapan notifikasi dibaca (null jika belum dibaca)
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
