<?php

class Books {
    public static $books = [];

    /**
    * retun all books
    */
    static function getAll() {
        return Books::$books;
    }

    /**
    * return a book with $id
    */
    static function getBook($id) {
        return isset(Books::$books[$id]) ? Books::$books[$id] : null;
    }

    /**
    * set a book with $book and $id (optional)
    */
    static function setBook($book, $id = null) {
        if ($id) {
            Books::$books[$id] = $book;
        } else {
            Books::$books = Books::$books + [$book->id => $book];
        }
    }

    /**
    * remove a book with $id from $books
    */
    static function removeBook($id) {
        if (isset(Books::$books[$id])) {
            unset(Books::$books[$id]);
        }
    }

    /**
    * return all books in json
    */
    static function getAllInJson() {
        $booksJson = [];
        foreach (Books::$books as $id => $book) {
            $booksJson[$id] = $book->jsonSerialize();
        }

        return $booksJson;
    }

    static function total() {
        return count(Books::$books);
    }
}