<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->text('content');
            $table->unsignedBigInteger('sended_from');
            $table->boolean('sceen')->default(0);
            $table->string('status')->default('1');
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('datetime');
            $table->timestamps();

            $table->foreign('sended_from')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
