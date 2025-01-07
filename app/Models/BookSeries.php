<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class BookSeries extends Model implements HasMedia
{
    use HasFactory, HasImage;

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('book_series') 
            ->useDisk('public')
            ->singleFile(); 
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
