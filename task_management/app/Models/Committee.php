<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Committee extends Model
{
    use HasFactory;

    public function user(): HasOne{
        return $this->hasOne(User::class);
    }
}
