<?php
class PieceProduct extends Product
{
    private int $pieces;

    public function __construct(string $name, float $basePrice, int $pieces)
    {
        parent::__construct($name, $basePrice);
        $this->pieces = $pieces;
    }

    public function calculateFinalPrice(): float
    {
        return $this->basePrice * $this->pieces;
    }
}
