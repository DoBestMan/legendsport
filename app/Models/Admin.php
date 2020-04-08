<?php
namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @mixin Eloquent
 */
class Admin extends Authenticatable
{
    use StaticTable;

    protected $table = 'admins';
    protected $hidden = ['password', 'remember_token'];
}
