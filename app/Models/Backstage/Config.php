<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'id';

    protected $casts = [
        'config'=>'json'
    ];
    
    protected $fillable = [
        'config',
        'config->chips',
        'config->conmission',
    ];

    public function getConfigAttribute($value)
    {
        $this->attributes['config'] = strtoupper($value);
    }
};