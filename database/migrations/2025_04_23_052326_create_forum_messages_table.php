<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forum_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message')->nullable();
            $table->enum('type', ['text', 'image'])->default('text');
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('reply_to_id')->nullable();
            $table->timestamps();
            $table->foreign('reply_to_id')->references('id')->on('forum_messages')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('forum_messages');
    }
};