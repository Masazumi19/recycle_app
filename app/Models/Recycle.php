<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
