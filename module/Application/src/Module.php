<?php

declare(strict_types=1);

namespace Application;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\ModuleRouteListener;
use Application\Listener\LayoutListener;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }


    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // Add layout listener
        $layoutListener = $e->getApplication()->getServiceManager()->get(LayoutListener::class);
        $layoutListener->attach($eventManager);
    }
}
