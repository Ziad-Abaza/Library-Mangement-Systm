<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Book extends Model implements HasMedia
{
    use HasFactory, HasImage;

    protected $fillable = [
        'title',
        'description',
        'published_at',
        'is_approved',
        'lang',
        'downloads_count',
        'views_count',
        'edition_number',
        'number_pages',
        'size',
        'publisher_name',
        'status',
        'book_series_id',
        'category_id',
        'user_id',
        'author_id',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'book_keyword');
    }

    public function bookSeries()
    {
        return $this->belongsTo(BookSeries::class, 'book_series_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_image')
            ->useDisk('public')
            ->singleFile(); 
            
        $this->addMediaCollection('file')
            ->useDisk('public')
            ->singleFile(); 

            $this->addMediaCollection('copyright_image')
            ->useDisk('public')
            ->singleFile(); 
    }
}
