# ai-books setup
- I used PHP 8.1 version
- You can put the whole folder or clone it inside any /var/www, so there shouldn't be any sub directory inorder the url to work.
- create .env file and add `GEMINI_API_KEY=[API_KEY]` and also `SECRET_KEY=[SECRET_KEY]`
- You need to have composer in command installed, then run `composer install` inorder to install necessary packages for unit testing and other

# ai-books auth
- to authenticate and get access token you can use `POST /api/login` using the following sample credential format in the json body
    ```
    {
      "username": "user",
      "password": "pass"
    }
    ```
- you can also signup and get access token via `POST /api/signup` the same format as login for now
- From the above authentication api, you will get result like
```
{
  "token":[token],
  "expires in": "2 hours"
}
```
# ai-books api
- you can use the above token to access the rest api endpoints forexample
```
-H "Accept: application/json"
-H "Content-Type: application/json"
-H "Auth: Bearer [token]"
GET /api/books
```

- Another example
```
-H "Accept: application/json"
-H "Content-Type: application/json"
-H "Auth: Bearer [token]"
-D "{'book_id': 1}"
POST /api/books/generate-summary
```
- the response to the above ai api (I use Gemni AI api here):
```
{
    "book": {
        "id": 1,
        "title": "Rich Dad Poor Dad",
        "author": "Robert Kiyosaki",
        "publishedYear": 1997
    },
    "summary": "**Rich Dad Poor Dad (1997) by Robert Kiyosaki**\n\n**Key Concepts:**\n\n* **The importance of financial literacy:** Rich Dad teaches the importance of understanding money, investing, and building wealth.\n* **\"Assets vs. liabilities\":** Assets are anything that puts money in your pocket, while liabilities take money out. Rich Dad emphasizes the need to acquire assets.\n* **The power of financial independence:** Financial independence is the ability to live off passive income generated from investments or businesses.\n* **The myth of traditional education:** Traditional education often does not prepare individuals for the real world, where financial literacy is crucial.\n* **The mindsets of the rich:** Rich people have a different mindset from the poor, including:\n * Willingness to take calculated risks\n * Focus on long-term wealth creation\n * Belief in the power of entrepreneurship\n\n**Major Lessons:**\n\n* **Teach your children financial literacy:** Parents should empower their children with financial knowledge and encourage them to become financially independent.\n* **Invest early and often:** Starting to invest as soon as possible helps compound returns and build wealth over time.\n* **Start a business:** Owning a business provides the potential for significant wealth creation and passive income.\n* **Acquire real assets:** Real estate, stocks, and other investments can generate passive income and increase your net worth.\n* **Change your mindset:** Adopt the mindset of the rich, including valuing financial education, taking risks, and focusing on the long term."
}
```

# ai-books test
- to test all functions you can run:
  `vendor/bin/phpunit tests/`

