<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'as_admins';
    protected $guarded = ['id'];
    protected $hidden  = ['password'];
}
