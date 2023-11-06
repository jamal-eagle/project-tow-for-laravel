<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ["name","travel_id","global_info",];

    public function travel()
    {
        return $this->belongsTo('App\Models\travel',"travel_id");
    }
    public function images()
    {
        return $this->hasMany('App\Models\image');
    }

}
