<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Model\ProductTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;

class IndexController extends AbstractActionController
{
    private $productTable;

    public function __construct(ProductTable $productTable)
    {
        $this->productTable = $productTable;
    }

    public function indexAction()
    {
        try {
            $products = $this->productTable->fetchAll();
            return new ViewModel(['products' => $products]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }
}
