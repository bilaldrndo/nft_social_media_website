<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

require "../vendor/autoload.php";
require "services/UserService.php";
require "services/NftService.php";
require "services/AdminService.php";

Flight::register('userService', 'UserService');
Flight::register('nftService', 'NftService');
Flight::register('adminService', 'AdminService');

session_start();

Flight::route('/*', function () {

    $path = Flight::request()->url;
    if ($path == '/signup' || $path == '/signin' || $path == '/docs.json')
        return TRUE;

    $headers = getallheaders();

    if (!$headers['Authorization']) {
        Flight::json(["message" => "Authorization is missing"], 403);
        return FALSE;
    } else {
        try {
            $decoded = (array) JWT::decode($headers['Authorization'], new Key('74ynr8y3487ry384r', 'HS256'));
            return TRUE;
        } catch (\Exception $e) {
            Flight::json(["message" => "Authorization token is not valid"], 403);
            return FALSE;
        }
    }
});

Flight::route('GET /docs.json', function () {
    $openapi = \OpenApi\scan('routes');
    header('Content-Type: application/json');
    echo $openapi->toJson();
});

require 'routes/UserRoutes.php';
require 'routes/NftRoutes.php';
require 'routes/AdminRoutes.php';

Flight::start();
?>