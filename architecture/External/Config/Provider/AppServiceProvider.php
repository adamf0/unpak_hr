<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\Auth\Authentication\AuthenticationCommand;
use Architecture\Application\Auth\Authentication\AuthenticationCommandHandler;
use Architecture\Application\Auth\LogOut\LogOutCommand;
use Architecture\Application\Auth\LogOut\LogOutCommandHandler;
use Architecture\Application\Dosen\GetAllDosenQuery;
use Architecture\Application\Dosen\GetInfoDosenQuery;
use Architecture\Application\Pegawai\FirstData\GetInfoPegawaiQuery;
use Architecture\Application\Pegawai\List\GetAllPegawaiQuery;
use Architecture\External\Persistance\Queries\Dosen\GetAllDosenQueryHandler;
use Architecture\External\Persistance\Queries\Dosen\GetInfoDosenQueryHandler;
use Architecture\External\Persistance\Queries\Pegawai\GetAllPegawaiQueryHandler;
use Architecture\External\Persistance\Queries\Pegawai\GetInfoPegawaiQueryHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $singletons = [
            ICommandBus::class                      => CommandBusImpl::class,
            IQueryBus::class                        => QueryBusImpl::class,
        ];

        foreach ($singletons as $abstract => $concrete) $this->app->singleton($abstract,$concrete);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(CommandBusImpl::class)->register([
            AuthenticationCommand::class => AuthenticationCommandHandler::class,
            LogOutCommand::class => LogOutCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllDosenQuery::class => GetAllDosenQueryHandler::class,
            GetInfoDosenQuery::class => GetInfoDosenQueryHandler::class,
            GetInfoPegawaiQuery::class => GetInfoPegawaiQueryHandler::class,
            GetAllPegawaiQuery::class => GetAllPegawaiQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
