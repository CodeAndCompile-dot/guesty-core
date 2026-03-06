<?php

namespace App\Providers;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All repository interface => implementation bindings.
     * Add new bindings here as models are introduced in later phases.
     *
     * @var array<class-string, class-string>
     */
    protected array $repositories = [
        \App\Repositories\Contracts\UserRepositoryInterface::class => \App\Repositories\Eloquent\UserRepository::class,
        \App\Repositories\Contracts\SettingRepositoryInterface::class => \App\Repositories\Eloquent\SettingRepository::class,
        \App\Repositories\Contracts\CmsRepositoryInterface::class => \App\Repositories\Eloquent\CmsRepository::class,
        \App\Repositories\Contracts\EmailTemplateRepositoryInterface::class => \App\Repositories\Eloquent\EmailTemplateRepository::class,
        \App\Repositories\Contracts\PropertyRepositoryInterface::class => \App\Repositories\Eloquent\PropertyRepository::class,
        \App\Repositories\Contracts\LocationRepositoryInterface::class => \App\Repositories\Eloquent\LocationRepository::class,
        \App\Repositories\Contracts\CouponRepositoryInterface::class => \App\Repositories\Eloquent\CouponRepository::class,
        \App\Repositories\Contracts\PropertyRateGroupRepositoryInterface::class => \App\Repositories\Eloquent\PropertyRateGroupRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }

        // Bind base repository for generic use
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
    }
}
