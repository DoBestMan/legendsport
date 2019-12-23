<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function tournamentPlayer()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentPlayer');
    }
}
