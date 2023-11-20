<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    protected $table = 'news_letter';
    protected $fillable = [
        'email', 'status'
    ];
}
