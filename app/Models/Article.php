<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'status',
        'article',
        'status',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
