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

    function getTitle() {
        return $this->title;
    }

    function getAuthor() {
        return $this->author;
    }

    function getPublishedYear() {
        return $this->publishedYear;
    }

    /**
    * add a book with $title, $author, $publishedYear
    */
    function add($title, $author, $publishedYear) {
        $this->id = Books::total() + 1;//incremental
        $this->title = $title;
        $this->author = $author;
        $this->publishedYear = $publishedYear;
        Books::setBook($this);
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