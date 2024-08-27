<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\Izin\Count\CountIzinQuery;
use Architecture\Application\Izin\Create\CreateIzinCommand;
use Architecture\Application\Izin\Create\CreateIzinCommandHandler;
use Architecture\Application\Izin\Delete\DeleteIzinCommand;
use Architecture\Application\Izin\Delete\DeleteIzinCommandHandler;
use Architecture\Application\Izin\FirstData\GetIzinQuery;
use Architecture\Application\Izin\List\GetAllIzinByNIDNQuery;
use Architecture\Application\Izin\List\GetAllIzinByNIPQuery;
use Architecture\Application\Izin\List\GetAllIzinInRangeQuery;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Application\Izin\Update\ApprovalIzinCommand;
use Architecture\Application\Izin\Update\ApprovalIzinCommandHandler;
use Architecture\Application\Izin\Update\UpdateIzinCommand;
use Architecture\Application\Izin\Update\UpdateIzinCommandHandler;
use Architecture\External\Persistance\Queries\Izin\CountIzinQueryHandler;
use Architecture\External\Persistance\Queries\Izin\GetAllIzinByNIDNQueryHandler;
use Architecture\External\Persistance\Queries\Izin\GetAllIzinByNIPQueryHandler;
use Architecture\External\Persistance\Queries\Izin\GetAllIzinInRangeQueryHandler;
use Architecture\External\Persistance\Queries\Izin\GetAllIzinQueryHandler;
use Architecture\External\Persistance\Queries\Izin\GetIzinQueryHandler;
use Illuminate\Support\ServiceProvider;

class IzinServiceProvider extends ServiceProvider
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
            CreateIzinCommand::class => CreateIzinCommandHandler::class,
            UpdateIzinCommand::class => UpdateIzinCommandHandler::class,
            DeleteIzinCommand::class => DeleteIzinCommandHandler::class,
            ApprovalIzinCommand::class => ApprovalIzinCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllIzinQuery::class             => GetAllIzinQueryHandler::class,
            GetAllIzinInRangeQuery::class      => GetAllIzinInRangeQueryHandler::class,
            GetAllIzinByNIDNQuery::class       => GetAllIzinByNIDNQueryHandler::class,
            GetAllIzinByNIPQuery::class        => GetAllIzinByNIPQueryHandler::class,
            GetIzinQuery::class                => GetIzinQueryHandler::class,
            CountIzinQuery::class              => CountIzinQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
