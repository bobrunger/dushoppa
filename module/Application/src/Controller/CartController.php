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

        // create a session container for cart
        $cart = new Container('cart');

        if (!isset($cart->items)) {
            $cart->items = [];
        }

        //add product to a cart or increase its qty
        if (!isset($cart->items[$productId])) {
            $cart->items[$productId] = 1;
        } else {
            $cart->items[$productId]++;
        }

        // writeToLog('Container test: ' . print_r($cart->items, true));

        // no render since its AJAX call
        //return $this->getResponse()->setContent("Product with id {$productId} added to cart");
        return $this->getResponse()->setContent(json_encode($cart->items));
    }

    public function removeOneAction()
    {
        $productId = $this->params()->fromQuery('productId'); // /add-to-cart?productId = 1


        $cart = new Container('cart');
        $miniCart = new Container('minicart');
        if (!isset($cart->items)) {
            $cart->items = [];
        }
        if (isset($cart->items[$productId])) {
            if ($cart->items[$productId] >= 0)
                $cart->items[$productId]--;
            else
                unset($cart->items[$productId]);
        }

        foreach ($miniCart->items as $key => $item) {
            if ($item['miniId'] == $productId) {
                if ($item['miniQty'] >= 0)
                    $item['miniQty']--;
                else
                    unset($miniCart->items[$key]);
                break;
            }
        }

        return $this->getResponse()->setContent(json_encode($cart->items));
    }

    public function removeAction()
    {
        $productId = $this->params()->fromQuery('productId'); // /add-to-cart?productId = 1

        $cart = new Container('cart');
        $miniCart = new Container('minicart');
        if (isset($cart->items[$productId])) {
            unset($cart->items[$productId]);
        }

        foreach ($miniCart->items as $key => $item) {
            if ($item['miniId'] == $productId) {
                unset($miniCart->items[$key]);
                break;
            }
        }


        return $this->getResponse()->setContent("Product with id {$productId} removed from cart");
    }

    public function viewAction()
    {
        $cart = new Container('cart');
        $productTable = $this->productTable;
        $cartProducts = [];
        //a
        //dd($cart->items);
        //b
        foreach ($cart->items as $productId => $qty) { // grab product id and quantity from session
            try {
                /** @var \Application\Model\Product $product */
                $product = $productTable->getProduct($productId);
                // dd($product);
                //dd($product->image);
                $cartProducts[] = [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'productPrice' => $product->price,
                    'productImage' => $product->image,
                    'productQty' => $qty,
                    'productTotalRowPrice' => $product->price * $qty
                ];
            } catch (\Exception $e) {
                //throw new Exception("error loading products {$e->getMessage()}");
                // Log the error or add it to the errors array
                $errors[] = "Error loading product with id {$productId}: {$e->getMessage()}";

                // Remove the non-existing product from the cart
                unset($cart->items[$productId]);
                writeToLog($errors);
            }
        }
        //dd($cartProducts);

        return new ViewModel(['cart' => $cartProducts]); // $this->cart , first argument is variable name
    }


    public function minicartAction()
    {
        $cart = new Container('cart');
        $miniCartProducts = new Container('minicart');
        if (!isset($miniCartProducts->items)) {
            $miniCartProducts->items = [];
        }
        $miniCart = [];
        foreach ($cart->items as $productId => $qty) {
            try {
                /** @var \Application\Model\Product $product */
                $product = $this->productTable->getProduct($productId);
                $miniCart[] = [
                    'miniId' => $product->id,
                    'miniQty' => $qty,
                    'miniName' => $product->name,
                    'miniPrice' => $product->price,
                    'miniImage' => $product->image,
                ];
            } catch (\Exception $e) {
                $errors[] = "Error loading product with id {$productId}: {$e->getMessage()}";
                unset($cart->items[$productId]);
                writeToLog($errors);
            }
        }
        $miniCartProducts->items = $miniCart;
        //sleep(3);

        return $this->getResponse()->setContent(json_encode($miniCart));
    }
}
