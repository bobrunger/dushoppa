<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;


return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'about' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/about',
                    'defaults' => [
                        'controller' => Controller\AboutController::class,
                        'action'     => 'about',
                    ],
                ],
            ],
            'contact' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/contact',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action'     => 'contact',
                    ],
                ],
            ],
            'cart' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cart[/:action]',
                    'defaults' => [
                        'controller' => Controller\CartController::class,
                        'action'     => 'add',
                    ],
                ],
            ],
            'cart-view' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/cart/view',
                    'defaults' => [
                        'controller' => Controller\CartController::class,
                        'action'     => 'view',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            // Controller\IndexController::class => InvokableFactory::class,
            // since IndexController consttructor has ProductTable parameter, the factory will instantiate Product table instance and pass it to the IndexController
            Controller\IndexController::class => ReflectionBasedAbstractFactory::class,
            Controller\AboutController::class => InvokableFactory::class,
            Controller\ContactController::class => InvokableFactory::class,
            Controller\CartController::class => ReflectionBasedAbstractFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\ProductTable::class => Model\ProductTableFactory::class,
            //Controller\IndexController::class => ReflectionBasedAbstractFactory::class
            //Controller\CartController::class => ReflectionBasedAbstractFactory::class,

        ],
    ],
];
