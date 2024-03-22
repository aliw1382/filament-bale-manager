<?php

namespace Aliw1382\FilamentBaleManager\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [ 'bot_id', 'to_chat_id', 'message' ];

}
