<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'id';

    public function tournamentPlayer()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentPlayer');
    }
}
