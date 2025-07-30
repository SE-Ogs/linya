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
        'summary',
        'status',
        'article',
        'status',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function featuredImage()
    {
        return $this->hasOne(Image::class)->where('is_featured', true);
    }

    public function addImage(string $path, bool $isFeatured = false, string $altText = null): images
    {
        return $this->images()->create([
            'path' => $path,
            'is_featured' => $isFeatured,
            'alt_text' => $altText,
        ]);


    }
}
