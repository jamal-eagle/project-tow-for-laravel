<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'bookings';
  protected $fillable = [
   'user_id','travel_id'
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\user','user_id');
  }
  public function travel()
  {
    return $this->belongsTo('App\Models\travel','travel_id');
  }
}
