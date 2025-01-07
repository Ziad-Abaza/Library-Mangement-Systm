<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class AuthorRequest extends Model implements HasMedia
{
    use HasImage, HasFactory;

    protected $fillable = [
        'name',
        'biography',
        'birthdate',
        'user_id',
        'status',
        'author_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('author_requests') 
            ->useDisk('public')
            ->singleFile(); 
    }
}
