<?php
class Review
{
    private int $productId;
    private float $rating;
    private string $comment;
    private User $user;

    public function __construct($productId, $rating, $comment, User $user)
    {
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->user = $user;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getComment()
    {
        return $this->comment;
    }
}
