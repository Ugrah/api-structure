<?php
require "../bootstrap.php";
use Src\Controller\PersonController;
use Src\Controller\VerificationController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// $_SERVER['REQUEST_URI'] = str_replace('.', '~~', $_SERVER['REQUEST_URI']);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// var_dump( apache_request_headers() ); exit();

// var_dump( apache_request_headers()['authtoken'] ); exit();




$authorizedResources = array('verifications', 'persons');
if (in_array($uri[1], $authorizedResources)) {
    // the user id is, of course, optional and must be a number:

    // $uri[2] = str_replace('~~', '.', $uri[2]);

    $userId = isset($uri[2]) ? (int) $uri[2] : null;
    

    // if (filter_var( $uri[2], FILTER_VALIDATE_EMAIL)) {
    //     if (isset($uri[2])) {
    //         $userId = $uri[2];
    //     }
    // } else {
    //     if (isset($uri[2])) {
    //         $userId = (int) $uri[2];
    //     }
    // }

    $requestMethod = $_SERVER["REQUEST_METHOD"];


    // pass the request method and user ID to the PersonController and process the HTTP request:
    if ( $uri[1] == 'persons') {
        $controller = new PersonController($dbConnection, $requestMethod, $userId);
    }
    if ( $uri[1] == 'verifications') {
        $controller = new VerificationController($dbConnection, $requestMethod, $userId);
    }
    $controller->processRequest();
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}