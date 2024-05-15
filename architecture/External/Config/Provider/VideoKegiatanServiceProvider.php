<?php

namespace Architecture\External\Config\Provider;

use Architecture\Application\Abstractions\Messaging\CommandBusImpl;
use Architecture\Application\Abstractions\Messaging\QueryBusImpl;
use Architecture\Application\VideoKegiatan\Create\CreateVideoKegiatanCommand;
use Architecture\Application\VideoKegiatan\Create\CreateVideoKegiatanCommandHandler;
use Architecture\Application\VideoKegiatan\Delete\DeleteVideoKegiatanCommand;
use Architecture\Application\VideoKegiatan\Delete\DeleteVideoKegiatanCommandHandler;
use Architecture\Application\VideoKegiatan\FirstData\GetVideoKegiatanQuery;
use Architecture\Application\VideoKegiatan\List\GetAllVideoKegiatanQuery;
use Architecture\Application\VideoKegiatan\Update\UpdateVideoKegiatanCommand;
use Architecture\Application\VideoKegiatan\Update\UpdateVideoKegiatanCommandHandler;
use Architecture\External\Persistance\Queries\VideoKegiatan\GetAllVideoKegiatanQueryHandler;
use Architecture\External\Persistance\Queries\VideoKegiatan\GetVideoKegiatanQueryHandler;
use Illuminate\Support\ServiceProvider;

class VideoKegiatanServiceProvider extends ServiceProvider
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
            CreateVideoKegiatanCommand::class => CreateVideoKegiatanCommandHandler::class,
            UpdateVideoKegiatanCommand::class => UpdateVideoKegiatanCommandHandler::class,
            DeleteVideoKegiatanCommand::class => DeleteVideoKegiatanCommandHandler::class,
        ]);

        app(QueryBusImpl::class)->register([
            GetAllVideoKegiatanQuery::class                => GetAllVideoKegiatanQueryHandler::class,
            GetVideoKegiatanQuery::class                => GetVideoKegiatanQueryHandler::class,
        ]);

        if(env('DEPLOY','dev')=='prod'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
            
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    }
}
