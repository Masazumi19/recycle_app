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
        'category_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    public function getImagePathAttribute()
    {
        return 'images/recycles/' . $this->image;
    }
}

 //public function image_url()
 //    {
 //        return Storage::url('images/recycless/' . $this->image);
 //    }
//アッパーキャメルけーすを復習
