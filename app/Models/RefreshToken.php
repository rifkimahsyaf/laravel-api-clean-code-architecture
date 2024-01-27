<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $incrementing = false;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];
}
