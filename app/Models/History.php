<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';
    protected $fillable = ['registration_id', 'random_number', 'is_win', 'win_sum'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
