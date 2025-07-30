<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'image_path',
        'alt_text',
        'order',
        'is_featured',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/'. $this->path);
    }
}
