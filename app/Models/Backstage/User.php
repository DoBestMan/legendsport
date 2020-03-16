<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function players()
    {
        return $this->hasMany(TournamentPlayer::class);
    }
}
