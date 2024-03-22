<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'messages', function ( Blueprint $table ) {
            $table->id();

            $table->foreignId( 'bot_id' )->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->string( 'to_chat_id' );

            $table->string( 'message', 4096 );

            $table->timestamps();
        } );
    }

    public function down() : void
    {
        Schema::dropIfExists( 'messages' );
    }
};
