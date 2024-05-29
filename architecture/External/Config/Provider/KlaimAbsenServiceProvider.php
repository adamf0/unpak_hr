<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\KlaimAbsen\Count\CountKlaimAbsenQuery;
use Architecture\Application\KlaimAbsen\Create\CreateKlaimAbsenCommand;
use Architecture\Application\KlaimAbsen\Create\CreateKlaimAbsenCommandHandler;
use Architecture\Application\KlaimAbsen\Delete\DeleteKlaimAbsenCommand;
use Architecture\Application\KlaimAbsen\Delete\DeleteKlaimAbsenCommandHandler;
use Architecture\Application\KlaimAbsen\FirstData\GetKlaimAbsenQuery;
use Architecture\Application\KlaimAbsen\List\GetAllKlaimAbsenQuery;
use Architecture\Application\KlaimAbsen\Update\ApprovalKlaimAbsenCommand;
use Architecture\Application\KlaimAbsen\Update\ApprovalKlaimAbsenCommandHandler;
use Architecture\Application\KlaimAbsen\Update\UpdateKlaimAbsenCommand;
use Architecture\Application\KlaimAbsen\Update\UpdateKlaimAbsenCommandHandler;
use Architecture\External\Persistance\Queries\KlaimAbsen\CountKlaimAbsenQueryHandler;
use Architecture\External\Persistance\Queries\KlaimAbsen\GetAllKlaimAbsenQueryHandler;
use Architecture\External\Persistance\Queries\KlaimAbsen\GetKlaimAbsenQueryHandler;
use Illuminate\Support\ServiceProvider;

class KlaimAbsenServiceProvider extends ServiceProvider
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
            CreateKlaimAbsenCommand::class => CreateKlaimAbsenCommandHandler::class,
            UpdateKlaimAbsenCommand::class => UpdateKlaimAbsenCommandHandler::class,
            DeleteKlaimAbsenCommand::class => DeleteKlaimAbsenCommandHandler::class,
            ApprovalKlaimAbsenCommand::class => ApprovalKlaimAbsenCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllKlaimAbsenQuery::class             => GetAllKlaimAbsenQueryHandler::class,
            GetKlaimAbsenQuery::class                => GetKlaimAbsenQueryHandler::class,
            CountKlaimAbsenQuery::class              => CountKlaimAbsenQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
