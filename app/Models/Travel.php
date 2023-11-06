<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'travels';
    protected $dates = ['start_date', 'end_date'];
    protected $fillable = ['manager_id', 'address','start_date', 'end_date', 'discription', 'participaints_num', 'price'];
    public function locations()
    {
        return $this->hasMany('App\Models\location');
    }

    public function supervisor()
    {
        return $this->belongsTo('App\Models\user','manager_id');
    }


    public function bookings()
    {
        return $this->hasMany('App\Models\booking');
    }

}
