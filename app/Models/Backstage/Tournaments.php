<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class Tournaments extends Model
{
    protected $table = 'tournaments';
    protected $primaryKey = 'id';

    protected $casts = [
        'late_register_rule' => 'json',
        'prize_pool' => 'json',
        'prizes' => 'json',
    ];

    public function players()
    {
        return $this->hasMany('App\Models\Backstage\TournamentPlayers');
    }

    public function events()
    {
        return $this->hasMany('App\Models\Backstage\TournamentEvents');
    }
}
