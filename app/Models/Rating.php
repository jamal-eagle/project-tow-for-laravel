<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'ratings';
    protected $fillable = [
        'rating', 'travel_id', 'user_id'
    ];
    public function mobile()
    {
        return $this->belongsTo('App\Models\travel');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
