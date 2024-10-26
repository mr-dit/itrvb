<?php
class WeightProduct extends Product
{
    private float $weightInKg;

    public function __construct(string $name, float $basePrice, float $weightInKg)
    {
        parent::__construct($name, $basePrice);
        $this->weightInKg = $weightInKg;
    }

    public function calculateFinalPrice(): float
    {
        return $this->basePrice * $this->weightInKg;
    }
}
