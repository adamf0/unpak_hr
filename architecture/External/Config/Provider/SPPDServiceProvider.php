<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\SPPD\Create\CreateSPPDCommand;
use Architecture\Application\SPPD\Create\CreateSPPDCommandHandler;
use Architecture\Application\SPPD\Delete\DeleteSPPDCommand;
use Architecture\Application\SPPD\Delete\DeleteSPPDCommandHandler;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIDNQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIPQuery;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Application\SPPD\Update\UpdateSPPDCommand;
use Architecture\Application\SPPD\Update\UpdateSPPDCommandHandler;
use Architecture\External\Persistance\Queries\SPPD\GetAllSPPDByNIDNQueryHandler;
use Architecture\External\Persistance\Queries\SPPD\GetAllSPPDByNIPQueryHandler;
use Architecture\External\Persistance\Queries\SPPD\GetAllSPPDQueryHandler;
use Architecture\External\Persistance\Queries\SPPD\GetSPPDQueryHandler;
use Illuminate\Support\ServiceProvider;

class SPPDServiceProvider extends ServiceProvider
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
            CreateSPPDCommand::class => CreateSPPDCommandHandler::class,
            UpdateSPPDCommand::class => UpdateSPPDCommandHandler::class,
            DeleteSPPDCommand::class => DeleteSPPDCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllSPPDQuery::class             => GetAllSPPDQueryHandler::class,
            GetAllSPPDByNIDNQuery::class       => GetAllSPPDByNIDNQueryHandler::class,
            GetAllSPPDByNIPQuery::class        => GetAllSPPDByNIPQueryHandler::class,
            GetSPPDQuery::class                => GetSPPDQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
