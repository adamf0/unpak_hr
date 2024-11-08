<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\Cuti\Count\CountCutiQuery;
use Architecture\Application\Cuti\Create\CreateCutiCommand;
use Architecture\Application\Cuti\Create\CreateCutiCommandHandler;
use Architecture\Application\Cuti\Delete\DeleteCutiCommand;
use Architecture\Application\Cuti\Delete\DeleteCutiCommandHandler;
use Architecture\Application\Cuti\FirstData\GetCutiQuery;
use Architecture\Application\Cuti\List\GetAllActiveCutiQuery;
use Architecture\Application\Cuti\List\GetAllCutiByNIDNQuery;
use Architecture\Application\Cuti\List\GetAllCutiByNIPQuery;
use Architecture\Application\Cuti\List\GetAllCutiInRangeQuery;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Application\Cuti\Update\ApprovalCutiCommand;
use Architecture\Application\Cuti\Update\ApprovalCutiCommandHandler;
use Architecture\Application\Cuti\Update\UpdateCutiCommand;
use Architecture\Application\Cuti\Update\UpdateCutiCommandHandler;
use Architecture\External\Persistance\Queries\Cuti\CountCutiQueryHandler;
use Architecture\External\Persistance\Queries\Cuti\GetAllActiveCutiQueryHandler;
use Architecture\External\Persistance\Queries\Cuti\GetAllCutiByNIDNQueryHandler;
use Architecture\External\Persistance\Queries\Cuti\GetAllCutiByNIPQueryHandler;
use Architecture\External\Persistance\Queries\Cuti\GetAllCutiInRangeQueryHandler;
use Architecture\External\Persistance\Queries\Cuti\GetAllCutiQueryHandler;
use Architecture\External\Persistance\Queries\Cuti\GetCutiQueryHandler;
use Illuminate\Support\ServiceProvider;

class CutiServiceProvider extends ServiceProvider
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
            CreateCutiCommand::class => CreateCutiCommandHandler::class,
            UpdateCutiCommand::class => UpdateCutiCommandHandler::class,
            DeleteCutiCommand::class => DeleteCutiCommandHandler::class,
            ApprovalCutiCommand::class => ApprovalCutiCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllCutiQuery::class             => GetAllCutiQueryHandler::class,
            GetAllCutiInRangeQuery::class      => GetAllCutiInRangeQueryHandler::class,
            GetAllActiveCutiQuery::class       => GetAllActiveCutiQueryHandler::class,
            GetAllCutiByNIDNQuery::class       => GetAllCutiByNIDNQueryHandler::class,
            GetAllCutiByNIPQuery::class        => GetAllCutiByNIPQueryHandler::class,
            GetCutiQuery::class                => GetCutiQueryHandler::class,
            CountCutiQuery::class              => CountCutiQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
