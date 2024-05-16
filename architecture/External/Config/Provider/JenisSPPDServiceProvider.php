<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\JenisSPPD\Create\CreateJenisSPPDCommand;
use Architecture\Application\JenisSPPD\Create\CreateJenisSPPDCommandHandler;
use Architecture\Application\JenisSPPD\Delete\DeleteJenisSPPDCommand;
use Architecture\Application\JenisSPPD\Delete\DeleteJenisSPPDCommandHandler;
use Architecture\Application\JenisSPPD\FirstData\GetJenisSPPDQuery;
use Architecture\Application\JenisSPPD\List\GetAllJenisSPPDQuery;
use Architecture\Application\JenisSPPD\Update\UpdateJenisSPPDCommand;
use Architecture\Application\JenisSPPD\Update\UpdateJenisSPPDCommandHandler;
use Architecture\External\Persistance\Queries\JenisSPPD\GetAllJenisSPPDQueryHandler;
use Architecture\External\Persistance\Queries\JenisSPPD\GetJenisSPPDQueryHandler;
use Illuminate\Support\ServiceProvider;

class JenisSPPDServiceProvider extends ServiceProvider
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
            CreateJenisSPPDCommand::class => CreateJenisSPPDCommandHandler::class,
            UpdateJenisSPPDCommand::class => UpdateJenisSPPDCommandHandler::class,
            DeleteJenisSPPDCommand::class => DeleteJenisSPPDCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllJenisSPPDQuery::class             => GetAllJenisSPPDQueryHandler::class,
            GetJenisSPPDQuery::class                => GetJenisSPPDQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
