<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\File::class => \App\Policies\FilePolicy::class,
        \App\Models\QrCode::class => \App\Policies\QrCodePolicy::class,
        \App\Models\VCardTemplate::class => \App\Policies\VCardTemplatePolicy::class,
        \App\Models\ShortLink::class => \App\Policies\ShortLinkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
