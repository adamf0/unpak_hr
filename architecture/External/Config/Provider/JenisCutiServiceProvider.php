<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\JenisCuti\Create\CreateJenisCutiCommand;
use Architecture\Application\JenisCuti\Create\CreateJenisCutiCommandHandler;
use Architecture\Application\JenisCuti\Delete\DeleteJenisCutiCommand;
use Architecture\Application\JenisCuti\Delete\DeleteJenisCutiCommandHandler;
use Architecture\Application\JenisCuti\FirstData\GetJenisCutiQuery;
use Architecture\Application\JenisCuti\List\GetAllJenisCutiQuery;
use Architecture\Application\JenisCuti\Update\UpdateJenisCutiCommand;
use Architecture\Application\JenisCuti\Update\UpdateJenisCutiCommandHandler;
use Architecture\External\Persistance\Queries\JenisCuti\GetAllJenisCutiQueryHandler;
use Architecture\External\Persistance\Queries\JenisCuti\GetJenisCutiQueryHandler;
use Illuminate\Support\ServiceProvider;

class JenisCutiServiceProvider extends ServiceProvider
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
            CreateJenisCutiCommand::class => CreateJenisCutiCommandHandler::class,
            UpdateJenisCutiCommand::class => UpdateJenisCutiCommandHandler::class,
            DeleteJenisCutiCommand::class => DeleteJenisCutiCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllJenisCutiQuery::class             => GetAllJenisCutiQueryHandler::class,
            GetJenisCutiQuery::class                => GetJenisCutiQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
