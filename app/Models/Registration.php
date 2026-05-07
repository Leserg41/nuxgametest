<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['username', 'phonenumber', 'unique_code', 'is_active'];

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
