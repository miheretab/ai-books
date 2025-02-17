<?php

include_once 'Books.php';

class Book {
    public int $id;
    private string $title;
    private string $author;
    private int $publishedYear;

    function __construct($id = 0, $title = "", $author = "", $publishedYear = 0) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->publishedYear = $publishedYear;
    }

    /**
    * add a book with $title, $author, $publishedYear
    */
    function add($title, $author, $publishedYear) {
        $id = Books::total() + 1;//incremental
        Books::setBook(new Book($id, $title, $author, $publishedYear));
    }

    /**
    * update the book with $title, $author, $publishedYear
    */
    function update($title, $author, $publishedYear) {
        $this->title = $title;
        $this->author = $author;
        $this->publishedYear = $publishedYear;
        Books::setBook($this, $this->id);
    }

    /**
    * remove the book
    */
    function remove() {
        Books::removeBook($this->id);
    }

    /**
    * prepare a class for json
    */
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}