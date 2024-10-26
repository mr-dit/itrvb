<?php
abstract class Product
{
    protected string $name;
    protected float $basePrice;

    public function __construct(string $name, float $basePrice)
    {
        $this->name = $name;
        $this->basePrice = $basePrice;
    }

    abstract public function calculateFinalPrice(): float;
}
