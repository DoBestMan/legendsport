<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function players()
    {
        return $this->hasMany('App\Models\Backstage\TournamentPlayers');
    }
}
