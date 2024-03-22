<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'bots', function ( Blueprint $table ) {
            $table->id();

            $table->foreignId( 'user_id' )->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->string( 'token', 50 )->unique();

            $table->string( 'name', 100 );

            $table->string( 'username', 50 );

            $table->string( 'bot_id', 30 );

            $table->integer( 'sort' )->nullable();

            $table->timestamps();
        } );
    }

    public function down() : void
    {
        Schema::dropIfExists( 'bots' );
    }
};
