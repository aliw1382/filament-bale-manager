<?php

namespace Aliw1382\FilamentBaleManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bot extends Model
{

    protected $fillable = [ 'user_id', 'token', 'username', 'bot_id' ];

    /**
     * @return HasMany
     */
    public function message() : HasMany
    {
        return $this->hasMany(Message::class);
    }

}
