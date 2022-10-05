<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;

        public function recycles()
    {
        return $this->hasMany(Recycle::class);
    }
}
