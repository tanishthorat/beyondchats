<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'source_url',
        'image_url',
        'is_processed',
        'references',
    ];

    protected $casts = [
        'is_processed' => 'boolean',
        'references' => 'array',
    ];
}
