<?php

require './vendor/autoload.php';

include_once './src/Book.php';
include_once './src/Books.php';
include_once './src/AI.php';

use PHPUnit\Framework\TestCase;

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

    public function testBookAddError() {
        $book = new Book();
        $result = $book->add("dsf", "sdf", "df");
        $book = Books::getBook(2);
        $this->assertNull($book);
    }

    public function testBookGet() {
        $book = Books::getBook(1);
        $this->assertEquals(1, Books::total());
        $this->assertEquals(1, $book->id);
    }

    public function testBookGetError() {
        $book = Books::getBook(2);
        $this->assertNull($book);
    }

    public function testBookUpdateError() {
        $book = Books::getBook(1);
        $book->update("Title", "author", "fg");
        $this->assertEquals("dsf", $book->getTitle());
    }

    public function testBookUpdate() {
        $book = Books::getBook(1);
        $book->update("Title", "author", 1556);
        $this->assertEquals(1, $book->id);
        $this->assertEquals("Title", $book->getTitle());
    }

    public function testBookSummaryError() {
        $book = new Book(1, "Rich Dad Poor Dad", "Robert", 1997);
        $summary = AI::getSummary($book);
        $this->assertArrayHasKey('error', $summary);
    }

    public function testBookSummary() {
        //setting env variable
        $env = file_get_contents(".env");
        $lines = explode("\n",$env);

        foreach($lines as $line){
          preg_match("/([^#]+)\=(.*)/",$line,$matches);
          if(isset($matches[2])){ putenv(trim($line)); }
        }


        $book = new Book(1, "Rich Dad Poor Dad", "Robert", 1997);
        $summary = AI::getSummary($book);
        $this->assertStringStartsWith('**Rich Dad', $summary);
    }

    public function testBookRemove() {
        $book = Books::getBook(1);
        $book->remove();
        $this->assertEquals(0, Books::total());
    }
}