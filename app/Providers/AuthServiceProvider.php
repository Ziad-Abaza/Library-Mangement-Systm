<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Book;
use App\Models\Category;
use App\Models\Download;
use App\Models\Role;
use App\Models\User;
use App\Models\CategoryGroup;
use App\Models\BookSeries;
use App\Policies\BookPolicy;
use App\Policies\DownloadPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CategoryGroupPolicy;
use App\Policies\BookSeriesPolicy;
use App\Models\AuthorRequest;
use App\Policies\AuthorRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
        Book::class => BookPolicy::class,
        CategoryGroup::class => CategoryGroupPolicy::class,
        Category::class => CategoryPolicy::class,
        AuthorRequest::class => AuthorRequestPolicy::class,
        BookSeries::class => BookSeriesPolicy::class,
        Download::class => DownloadPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
