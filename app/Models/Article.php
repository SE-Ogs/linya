<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'summary',
        'status',
        'article',
        'status',
        'views',
        'rejection_reason',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user', 'replies.user');
    }

    public function images()
    {
        return $this->hasMany(ArticleImage::class)->orderBy('order');
    }

    public function featuredImage()
    {
        return $this->hasOne(ArticleImage::class)->where('is_featured', true);
    }

    public function getFirstImageAttribute()
    {
        return $this->featuredImage ?? $this->images()->first();
    }

    // For navigation between articles
    public function getPrevious()
    {
        return static::where('id', '<', $this->id)->orderBy('id', 'desc')->first();
    }

    public function getNext()
    {
        return static::where('id', '>', $this->id)->orderBy('id', 'asc')->first();
    }


}
