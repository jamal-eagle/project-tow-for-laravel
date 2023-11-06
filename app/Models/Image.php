<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'images';
  protected $fillable = [
   'url', 'location_id'
  ];
public function location()
{
  return $this->belongsTo('App\Models\location','location_id');
}
}
