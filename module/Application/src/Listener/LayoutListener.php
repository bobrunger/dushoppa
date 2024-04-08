<?php

declare(strict_types=1);
// Step 1: Create an event listener class
namespace Application\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;

class LayoutListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    public function onDispatch(MvcEvent $event)
    {
        $viewModel = $event->getViewModel();
        $miniCart = new Container('minicart');
        if (!isset($miniCart->items)) {
            $miniCart->items = [];
        }
        $viewModel->setVariable('miniCartProducts', $miniCart->items); // cart products should be available in layout (header) as well as globally (eg about, contact us etc)
    }
}
