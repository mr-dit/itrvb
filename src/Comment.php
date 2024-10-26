<?php

namespace MyApp;

class Comment
{
    public $id;
    public $authorId;
    public $articleId;
    public $text;

    public function __construct($id, $authorId, $articleId, $text)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->articleId = $articleId;
        $this->text = $text;
    }
}
