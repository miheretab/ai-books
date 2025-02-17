<?php

include_once "router.php";
include_once "Book.php";
include_once "Books.php";
$router = new Router();

//initiate book array with existing sample books
Books::setBook(new Book(1, "Rich Dad Poor Dad", "Robert Kiyosaki", 1997), 1);
Books::setBook(new Book(2, "Harry Potter", "J. K. Rowling", 1997), 2);
Books::setBook(new Book(3, "The Great Gatsby", "F. Scott Fitzgerald", 1925), 3);
Books::setBook(new Book(4, "Think and Grow Rich", "Napoleon Hill", 1937), 4);

/*
** `GET /api/books` - Retrieve a list of all books.
*/
$router->addRoute('GET', '/api/books', function () {
    $books = Books::getAllInJson();

    echo json_encode($books);
    return;
});

/**
* `GET /api/books/{id}` - Retrieve a single book by its ID.
*/
$router->addRoute('GET', '/api/books/{id}', function ($id) {
    $book = Books::getBook($id);

    if (!$book) {
        echo json_encode("No book found.");
        return;
    }

    echo json_encode($book->jsonSerialize());
    return;
});

/**
* `POST /api/books` - Add a new book to the list.
*/
$router->addRoute('POST', '/api/books', function () {
    $request = json_decode(file_get_contents("php://input"), true);

    if (!isset($request['title']) || !isset($request['author']) || !isset($request['publishedYear'])) {
        echo json_encode("All fields are required");
        return;
    }

    $book = new Book();
    $book->add($request['title'], $request['author'], $request['publishedYear']);
    echo json_encode("Book added successfully");
    return;
});

/**
* `PUT /api/books/{id}` - Update an existing book.
*/
$router->addRoute('PUT', '/api/books/{id}', function ($id) {
    $request = json_decode(file_get_contents("php://input"), true);

    if (!isset($request['title']) || !isset($request['author']) || !isset($request['publishedYear'])) {
        echo json_encode("All fields are required");
        return;
    }

    $book = Books::getBook($id);
    $book->update($request['title'], $request['author'], $request['publishedYear']);

    echo json_encode("Book updated successfully");
    return;
});

/**
* `DELETE /api/books/{id}` - Delete a book by its ID.
*/
$router->addRoute('DELETE', '/api/books/{id}', function ($id) {
    $book = Books::getBook($id);
    $book->remove();

    echo json_encode("Book removed successfully");
    return;
});

$router->matchRoute();