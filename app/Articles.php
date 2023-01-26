<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $fillable = [
        'title', 'image', 'description', 'cicle_id'
    ];

}
