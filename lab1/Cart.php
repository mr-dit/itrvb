<?php
class Cart
{
    private array $products;
    private float $totalPrice;

    public function __construct($products = [], $totalPrice = 0)
    {
        $this->$products = $products;
        $this->totalPrice = $totalPrice;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
        $this->totalPrice += $product->getPrice();
    }

    public function removeProduct($productId)
    {
        foreach ($this->products as $key => $product) {
            if ($product->getId() == $productId) {
                $this->totalPrice -= $product->getPrice();
                unset($this->products[$key]);
                break;
            }
        }
    }

    public function getTotal()
    {
        return $this->totalPrice;
    }

    public function getProducts()
    {
        return $this->products;
    }
}
