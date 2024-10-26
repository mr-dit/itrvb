<?php
class DigitalProduct extends Product
{
    public function calculateFinalPrice(): float
    {
        return $this->basePrice / 2;
    }
}
