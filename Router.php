<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Router {
    protected $routes = []; // stores routes
    private $secretKey = 'your_secret_key'; // Replace with your actual secret key
    private $allowed = ["/api/signup", "/api/login"];
    private $tokenExpiryTime = "2 hours";

    public function addRoute(string $method, string $url, closure $target) {
        $this->routes[$method][$url] = $target;
    }

    public function matchRoute() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $target) {
                // Use named subpatterns in the regular expression pattern to capture each parameter value separately
                $pattern = preg_replace('/\/{([^\/]+)\}/', '/(?P<$1>[^/]+)', $routeUrl);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    if (in_array($url, $this->allowed) || $this->handleRequest()) {
                        // Pass the captured parameter values as named arguments to the target function
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Only keep named subpattern matches
                        call_user_func_array($target, $params);
                    }
                    return;
                }
            }
        }
        //throw new Exception('Route not found');
        echo json_encode(['message' => 'Route not found']);
    }

    public function handleRequest() {
        $headers = apache_request_headers();
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            list($jwt) = sscanf($authHeader, 'Bearer %s');

            if ($jwt) {
                try {
                    $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
                    return true;

                } catch (Exception $e) {
                    header("HTTP/1.1 401 Unauthorized");
                    echo json_encode(['message' => 'Access denied', 'error' => $e->getMessage()]);
                    //throw new Exception('Access denied');
                }
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['message' => 'Invalid token format']);
                //throw new Exception('Invalid token format');
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['message' => 'Authorization header not found']);
            //throw new Exception('Authorization header not found');
        }

        return false;
    }

    public function handleLoginRequest($input)
    {
        if (isset($input['username']) && isset($input['password'])) {//you can check password validity using hash here if it is DB
            $token = $this->generateToken($input['username']);
            echo json_encode(['token' => $token, 'expires in' => $this->tokenExpiryTime]);
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['message' => 'Invalid input']);
        }
    }

    private function generateToken($username)
    {
        $payload = [
            'iss' => "http://local.books.com",
            'aud' => "http://local.books.com",
            'iat' => time(),
            'nbf' => time(),
            'exp' => strtotime("+" . $this->tokenExpiryTime),
            'username' => $username
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }
}