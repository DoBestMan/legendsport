<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentSports extends Model
{
    protected $table = 'tournament_sports';
    protected $primaryKey = 'id';
    protected $fillable = ['tournament_id', 'sport_id'];

    public function tournaments()
    {
        return $this->belongsTo('App\Models\Backstage\Tournaments');
    }
}
