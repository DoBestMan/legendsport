<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class Tournaments extends Model
{
    protected $table = 'tournaments';
    protected $primaryKey = 'id';

    protected $casts = [
        'late_register_rule' => 'json',
        'prizes' => 'array',
    ];


    public function tournamentPlayer()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentPlayer');
    }

    public function tournamentEvents()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentEvents');
    }
}
