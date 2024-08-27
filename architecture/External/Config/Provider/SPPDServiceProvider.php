<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\SPPD\Create\CreateAnggotaSPPDCommand;
use Architecture\Application\SPPD\Create\CreateAnggotaSPPDCommandHandler;
use Architecture\Application\SPPD\Create\CreateSPPDCommand;
use Architecture\Application\SPPD\Create\CreateSPPDCommandHandler;
use Architecture\Application\SPPD\Create\CreateSPPDLaporanFileCommand;
use Architecture\Application\SPPD\Create\CreateSPPDLaporanFileCommandHandler;
use Architecture\Application\SPPD\Delete\DeleteAllAnggotaSPPDCommand;
use Architecture\Application\SPPD\Delete\DeleteAllAnggotaSPPDCommandHandler;
use Architecture\Application\SPPD\Delete\DeleteAllSPPDLaporanFileCommand;
use Architecture\Application\SPPD\Delete\DeleteAllSPPDLaporanFileCommandHandler;
use Architecture\Application\SPPD\Delete\DeleteSPPDCommand;
use Architecture\Application\SPPD\Delete\DeleteSPPDCommandHandler;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIDNQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIPQuery;
use Architecture\Application\SPPD\List\GetAllSPPDInRangeQuery;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Application\SPPD\Update\ApprovalSPPDCommand;
use Architecture\Application\SPPD\Update\ApprovalSPPDCommandHandler;
use Architecture\Application\SPPD\Update\RejectSPPDCommand;
use Architecture\Application\SPPD\Update\RejectSPPDCommandHandler;
use Architecture\Application\SPPD\Update\UpdateAnggotaSPPDCommand;
use Architecture\Application\SPPD\Update\UpdateAnggotaSPPDCommandHandler;
use Architecture\Application\SPPD\Update\UpdateSPPDCommand;
use Architecture\Application\SPPD\Update\UpdateSPPDCommandHandler;
use Architecture\Application\SPPD\Update\UpdateSPPDLaporanCommand;
use Architecture\Application\SPPD\Update\UpdateSPPDLaporanCommandHandler;
use Architecture\External\Persistance\Queries\SPPD\GetAllSPPDByNIDNQueryHandler;
use Architecture\External\Persistance\Queries\SPPD\GetAllSPPDByNIPQueryHandler;
use Architecture\External\Persistance\Queries\SPPD\GetAllSPPDInRangeQueryHandler;
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
            RejectSPPDCommand::class => RejectSPPDCommandHandler::class,
            ApprovalSPPDCommand::class => ApprovalSPPDCommandHandler::class,
            CreateSPPDLaporanFileCommand::class => CreateSPPDLaporanFileCommandHandler::class,

            CreateAnggotaSPPDCommand::class => CreateAnggotaSPPDCommandHandler::class,
            UpdateAnggotaSPPDCommand::class => UpdateAnggotaSPPDCommandHandler::class,
            UpdateSPPDLaporanCommand::class => UpdateSPPDLaporanCommandHandler::class,
            DeleteAllAnggotaSPPDCommand::class => DeleteAllAnggotaSPPDCommandHandler::class,
            DeleteAllSPPDLaporanFileCommand::class => DeleteAllSPPDLaporanFileCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllSPPDQuery::class             => GetAllSPPDQueryHandler::class,
            GetAllSPPDInRangeQuery::class      => GetAllSPPDInRangeQueryHandler::class,
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
