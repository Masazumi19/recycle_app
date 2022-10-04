<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Recycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'price',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::url('images/recycles/' . $this->image); //
    }
}

 //public function image_url()
 //    {
 //        return Storage::url('images/recycless/' . $this->image);
 //    }
//アッパーキャメルけーすを復習
