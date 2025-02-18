<?php

include_once "Router.php";
include_once "src/Book.php";
include_once "src/Books.php";
include_once "src/AI.php";

$router = new Router();

//initiate book array with existing sample books
Books::setBook(new Book(1, "Rich Dad Poor Dad", "Robert Kiyosaki", 1997), 1);
Books::setBook(new Book(2, "Harry Potter", "J. K. Rowling", 1997), 2);
Books::setBook(new Book(3, "The Great Gatsby", "F. Scott Fitzgerald", 1925), 3);
Books::setBook(new Book(4, "Think and Grow Rich", "Napoleon Hill", 1937), 4);

//sample user db to save
$users = [];

/*
** `GET /api/signup` - signup to get access.
*/
$router->addRoute('POST', '/api/signup', function () use ($router) {
    //username and password should be saved here
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['username']) && isset($input['password'])) {
        $users[] = ["username" => $input["username"], "password" => $input["password"]];
    }
    //generate token here
    $router->handleLoginRequest($input);
    return;
});

/*
** `GET /api/login` - authentication to get access token.
*/
$router->addRoute('POST', '/api/login', function () use ($router) {
    $input = json_decode(file_get_contents('php://input'), true);
    $router->handleLoginRequest($input);
    return;
});

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

    if (!$book) {
        echo json_encode("No book found.");
        return;
    }

    $book->remove();

    echo json_encode("Book removed successfully");
    return;
});

/**
* `POST /api/books/generate-summary` - get generated summary from AI.
*/
$router->addRoute('POST', '/api/books/generate-summary', function () {
    $request = json_decode(file_get_contents("php://input"), true);
    if (!isset($request['book_id'])) {
        echo json_encode("Book id is required");
        return;
    }

    $id = $request['book_id'];

    $book = Books::getBook($id);
    if (!$book) {
        echo json_encode("No book found.");
        return;
    }

    //here generate with the help of AI
    $summary = AI::getSummary($book);
    /*$summary = DeepSeekClient::build('sk-1cd9ea00e1b74f1ca02c83d9769fc06e')
        ->query('Summarize ' . $book->getTitle() . ' Book by ' . $book->getAuthor() . ' published at ' . $book->getPublishedYear())
        ->run();*/

    echo json_encode(["book" => $book->jsonSerialize(), "summary" => $summary]);
    return;
});

$router->matchRoute();