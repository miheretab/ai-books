# ai-books setup
- I used PHP 8.1 version
- You can put the whole folder or clone it inside any /var/www, so there shouldn't be any sub directory inorder the url to work.
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
# ai-books test
- to test all functions you can run:
  `vendor/bin/phpunit tests/`

  
Auth
