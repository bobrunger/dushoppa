<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;
use Application\Model\ProductTable;
use Exception;

class CartController extends AbstractActionController
{
    private $productTable;

    public function __construct(ProductTable $productTable) // reflectionbasedabstractfactory will instantiate the product table
    {
        $this->productTable = $productTable;
    }

    public function addAction()
    {
        $productId = $this->params()->fromQuery('productId'); // /add-to-cart?productId = 1
        $cart = new Container('cart');
        $productTable = $this->productTable;
        $cartProducts = [];
        $addedMsg = "";

        if (!isset($cart->items)) {
            $cart->items = [];
        }

        if (!isset($cart->items[$productId])) {
            $cart->items[$productId] = 1;
        } else {
            $cart->items[$productId]++;
        }

        foreach ($cart->items as $productId => $qty) {
            try {
                /** @var \Application\Model\Product $product */
                $product = $productTable->getProduct($productId);
                $cartProducts[$productId] = [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'productPrice' => $product->price,
                    'productImage' => $product->image,
                    'productQty' => $qty,
                    'productTotalRowPrice' => $product->price * $qty
                ];
                $addedMsg = "{$product->name} was added to cart";
            } catch (\Exception $e) {
                $errors[] = "Error loading product with id {$productId}: {$e->getMessage()}";
                unset($cart->items[$productId]);
                writeToLog($errors);
            }
        }

        $cart->products = $cartProducts;

        $response = ["msg" => $addedMsg, "cart" => $cart->products];

        return $this->getResponse()->setContent(json_encode($response));
    }

    public function removeAction()
    {
        $productId = $this->params()->fromQuery('productId'); // /add-to-cart?productId = 1

        $cart = new Container('cart');

        if (isset($cart->items[$productId]))
            unset($cart->items[$productId]);
        if (isset($cart->products[$productId]))
            unset($cart->products[$productId]);

        return $this->getResponse()->setContent("Product with id {$productId} removed from cart");
    }

    public function removeOneAction()
    {
        $productId = $this->params()->fromQuery('productId'); // /add-to-cart?productId = 1

        $cart = new Container('cart');
        $productName = "";

        if (isset($cart->items[$productId])) {
            if ($cart->items[$productId] > 1)
                $cart->items[$productId]--;
            else
                unset($cart->items[$productId]);
        }
        if (isset($cart->products[$productId])) {
            $productName = $cart->products[$productId]['productName'];
            if ($cart->products[$productId]["productQty"] > 1) {
                $cart->products[$productId]["productQty"]--;
                $cart->products[$productId]["productTotalRowPrice"] = $cart->products[$productId]["productQty"] * $cart->products[$productId]["productPrice"];
            } else
                unset($cart->products[$productId]);
        }

        $addedMsg = "{$productName} was removed from cart";
        $response = ["msg" => $addedMsg, "cart" => $cart->products];

        return $this->getResponse()->setContent(json_encode($response));
    }

    public function viewAction()
    {
        $cart = new Container('cart');

        if (!isset($cart->products)) {
            $cart->products = [];
        }
        return new ViewModel(['cart' => $cart->products]); // $this->cart , first argument is variable name
    }
}
