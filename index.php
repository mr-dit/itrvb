<?php
require_once './Product.php';
require_once './DigitalProduct.php';
require_once './PieceProduct.php';
require_once './WeightProduct.php';

$digitalProduct = new DigitalProduct("E-Book", 20);
echo "Digital product final price: " . $digitalProduct->calculateFinalPrice() . '<br>';

$pieceProduct = new PieceProduct("Smartphone", 300, 2);
echo "Piece product final price: " . $pieceProduct->calculateFinalPrice() . '<br>';

$weightProduct = new WeightProduct("Apples", 3, 5);
echo "Weight product final price: " . $weightProduct->calculateFinalPrice() . '<br>';
