<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarQuery;
use Architecture\External\Persistance\Queries\MasterKalendar\GetAllMasterKalendarQueryHandler;
use Illuminate\Support\ServiceProvider;

class MasterKalendarServiceProvider extends ServiceProvider
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
            // CreateMasterKalendarCommand::class => CreateMasterKalendarCommandHandler::class,
            // UpdateMasterKalendarCommand::class => UpdateMasterKalendarCommandHandler::class,
            // DeleteMasterKalendarCommand::class => DeleteMasterKalendarCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllMasterKalendarQuery::class             => GetAllMasterKalendarQueryHandler::class,
            // GetMasterKalendarQuery::class                => GetMasterKalendarQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
