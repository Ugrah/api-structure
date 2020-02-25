<?php
require "../bootstrap.php";
use Src\Controller\PersonController;
use Src\Controller\EmailVerificationController;
use Src\Controller\PhoneVerificationController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );


$authorizedResources = array('person', 'email-verification', 'phone-verification');
if (in_array($uri[1], $authorizedResources)) {
    // the user id is, of course, optional and must be a number:
    $userId = null;
    if (isset($uri[2])) {
        $userId = (int) $uri[2];
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];

    // pass the request method and user ID to the PersonController and process the HTTP request:
    if ( $uri[1] == 'person') {
        $controller = new PersonController($dbConnection, $requestMethod, $userId);
    }
    if ( $uri[1] == 'email-verification') {
        $controller = new EmailVerificationController($dbConnection, $requestMethod, $userId);
    }
    if ( $uri[1] == 'phone-verification') {
        $controller = new PhoneVerificationController($dbConnection, $requestMethod, $userId);
    }
    $controller->processRequest();
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}