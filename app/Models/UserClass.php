<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    // protected $factory = '';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
