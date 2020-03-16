<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'config';

    public function getConfigAttribute($value)
    {
        $value = json_decode($value, true);

        $config = json_encode([
            'chips' => Arr::get($value, 'chips') ?? 10000,
            'commission' => Arr::get($value, 'commission') ?? 2,
            'keep_completed' => Arr::get($value, 'keep_completed') ?? 1,
        ]);

        return json_decode($config, true);
    }
};
