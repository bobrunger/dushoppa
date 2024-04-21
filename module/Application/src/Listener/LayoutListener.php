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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onDispatch'], 100);
    }

    public function onDispatch(MvcEvent $event)
    {
        $viewModel = $event->getViewModel();
        $cart = new Container('cart');
        $cartCount = 0;

        if (!isset($cart->products)) {
            $cart->products = [];
        }

        foreach ($cart->products as $item => $product) {
            $cartCount += $product["productQty"];
        }

        $viewModel->setVariable('cartCount', $cartCount);
        $viewModel->setVariable('miniCart', $cart->products);
    }
}
