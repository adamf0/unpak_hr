<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\Pengguna\Create\CreatePenggunaCommand;
use Architecture\Application\Pengguna\Create\CreatePenggunaCommandHandler;
use Architecture\Application\Pengguna\Delete\DeletePenggunaCommand;
use Architecture\Application\Pengguna\Delete\DeletePenggunaCommandHandler;
use Architecture\Application\Pengguna\FirstData\GetPenggunaQuery;
use Architecture\Application\Pengguna\List\GetAllPenggunaQuery;
use Architecture\Application\Pengguna\Update\UpdatePenggunaCommand;
use Architecture\Application\Pengguna\Update\UpdatePenggunaCommandHandler;
use Architecture\External\Persistance\Queries\Pengguna\GetAllPenggunaQueryHandler;
use Architecture\External\Persistance\Queries\Pengguna\GetPenggunaQueryHandler;
use Illuminate\Support\ServiceProvider;

class PenggunaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $singletons = [

        ];

        foreach ($singletons as $abstract => $concrete) $this->app->singleton($abstract,$concrete);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(CommandBusImpl::class)->register([
            CreatePenggunaCommand::class => CreatePenggunaCommandHandler::class,
            UpdatePenggunaCommand::class => UpdatePenggunaCommandHandler::class,
            DeletePenggunaCommand::class => DeletePenggunaCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllPenggunaQuery::class             => GetAllPenggunaQueryHandler::class,
            GetPenggunaQuery::class                => GetPenggunaQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
