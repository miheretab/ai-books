<?php

use PHPUnit\Framework\TestCase;
include_once 'Book.php';
include_once 'Books.php';

class BookTest extends TestCase {

    public function testBookConstructor() {
        $book = new Book(1, "dsf", "sdf", 1556);
        $this->assertEquals(1, $book->id);
    }

    public function testBookAdd() {
        $book = new Book();
        $book->add("dsf", "sdf", 1556);
        $this->assertEquals(1, $book->id);
        $this->assertEquals(1, Books::total());
    }

    public function testBookGet() {
        $book = Books::getBook(1);
        $this->assertEquals(1, Books::total());
        $this->assertEquals(1, $book->id);
    }

    public function testBookUpdate() {
        $book = Books::getBook(1);
        $book->update("Title", "author", 1556);
        $this->assertEquals(1, $book->id);
        $this->assertEquals("Title", $book->getTitle());
    }

    public function testBookRemove() {
        $book = Books::getBook(1);
        $book->remove();
        $this->assertEquals(0, Books::total());
    }
}