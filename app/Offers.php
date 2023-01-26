<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $fillable = [
        'title', 'description', 'date_max', 'num_candidates', 'cicle_id'
    ];

}
