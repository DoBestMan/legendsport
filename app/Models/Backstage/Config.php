<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'config';

    public function getConfigAttribute($value)
    {
        $value = json_decode($value, true);

        $config = json_encode([
            'chips' => $value['chips'] == null ? 10000 : $value['chips'],
            'commission' => $value['commission'] == null ? 2 : $value['commission'], 
            'keep_completed' => $value['keep_completed'] == null ? 1 : $value['keep_completed'], 
        ]);
        
        return json_decode($config, true);;
    }
};