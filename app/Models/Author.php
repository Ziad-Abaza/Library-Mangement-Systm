<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Author extends Model implements HasMedia
{
    use HasImage, HasFactory;

    protected $fillable = [
        'name',
        'biography',
        'birthdate',
        'user_id',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authorRequests()
    {
        return $this->hasMany(AuthorRequest::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('authors') 
            ->useDisk('public')
            ->singleFile(); 
    }
}
