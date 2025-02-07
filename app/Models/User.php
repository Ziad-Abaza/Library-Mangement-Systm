<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use APP\Notifications\GeneralNotification;
use Illuminate\Notifications\DatabaseNotification as Notification;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'token',
        'token_expiration',
        'is_active',
    ];



    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public  function authorRequest()
    {
        return $this->hasMany(AuthorRequest::class);
    }

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public  function bookSeries()
    {
        return $this->hasMany(BookSeries::class);
    }

    public  function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission($permissionName)
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->contains('name', $permissionName);
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function getRoleLevel()
    {
        return $this->roles()->first()->role_level ?? 1;
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }


    public function markNotificationsAsRead()
    {
        $this->unreadNotifications->markAsRead();
    }

    public function sendNotification($message)
    {
        $this->notify(new GeneralNotification($message));
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
