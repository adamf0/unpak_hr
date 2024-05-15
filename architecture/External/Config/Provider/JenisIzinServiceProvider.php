<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\JenisIzin\Create\CreateJenisIzinCommand;
use Architecture\Application\JenisIzin\Create\CreateJenisIzinCommandHandler;
use Architecture\Application\JenisIzin\Delete\DeleteJenisIzinCommand;
use Architecture\Application\JenisIzin\Delete\DeleteJenisIzinCommandHandler;
use Architecture\Application\JenisIzin\FirstData\GetJenisIzinQuery;
use Architecture\Application\JenisIzin\List\GetAllJenisIzinQuery;
use Architecture\Application\JenisIzin\Update\UpdateJenisIzinCommand;
use Architecture\Application\JenisIzin\Update\UpdateJenisIzinCommandHandler;
use Architecture\External\Persistance\Queries\JenisIzin\GetAllJenisIzinQueryHandler;
use Architecture\External\Persistance\Queries\JenisIzin\GetJenisIzinQueryHandler;
use Illuminate\Support\ServiceProvider;

class JenisIzinServiceProvider extends ServiceProvider
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
            CreateJenisIzinCommand::class => CreateJenisIzinCommandHandler::class,
            UpdateJenisIzinCommand::class => UpdateJenisIzinCommandHandler::class,
            DeleteJenisIzinCommand::class => DeleteJenisIzinCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllJenisIzinQuery::class             => GetAllJenisIzinQueryHandler::class,
            GetJenisIzinQuery::class                => GetJenisIzinQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
