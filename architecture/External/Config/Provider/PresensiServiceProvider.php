<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\Presensi\Create\CreatePresensiKeluarCommand;
use Architecture\Application\Presensi\Create\CreatePresensiKeluarCommandHandler;
use Architecture\Application\Presensi\Create\CreatePresensiMasukCommand;
use Architecture\Application\Presensi\Create\CreatePresensiMasukCommandHandler;
use Architecture\Application\Presensi\FirstData\GetPresensiQuery;
use Architecture\Application\Presensi\List\GetAllPresensiByNIDNQuery;
use Architecture\Application\Presensi\List\GetAllPresensiByNIPQuery;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\External\Persistance\Queries\Presensi\GetAllPresensiByNIDNQueryHandler;
use Architecture\External\Persistance\Queries\Presensi\GetAllPresensiByNIPQueryHandler;
use Architecture\External\Persistance\Queries\Presensi\GetAllPresensiQueryHandler;
use Architecture\External\Persistance\Queries\Presensi\GetPresensiQueryHandler;
use Illuminate\Support\ServiceProvider;

class PresensiServiceProvider extends ServiceProvider
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
            CreatePresensiMasukCommand::class => CreatePresensiMasukCommandHandler::class,
            CreatePresensiKeluarCommand::class => CreatePresensiKeluarCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllPresensiQuery::class             => GetAllPresensiQueryHandler::class,
            GetAllPresensiByNIDNQuery::class       => GetAllPresensiByNIDNQueryHandler::class,
            GetAllPresensiByNIPQuery::class        => GetAllPresensiByNIPQueryHandler::class,
            GetPresensiQuery::class                => GetPresensiQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
