<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Zenstruck\JWT\Signer\HMAC\HS256;
use Zenstruck\JWT\Token;
use Zenstruck\JWT\Validator\ExpiresAtValidator;
use Zenstruck\JWT\Validator\IssuerValidator;
use Zenstruck\JWT\Exception\MalformedToken;
use Zenstruck\JWT\Exception\Validation\ExpiredToken;
use Zenstruck\JWT\Exception\ValidationFailed;
use Zenstruck\JWT\Exception\UnverifiedToken;

define('APP_NAME', 'Xdem SSR Poc');
require '../vendor/autoload.php';
require_once '../config.php';
require_once 'functions.php';
$conn = null;
try
{  
    $dbhost = DB_HOST;
    $dbport = DB_PORT;
    $dbuser = DB_USERNAME;
    $dbpass = DB_PASSWORD;
    $dbname = DB_DATABASE;
	$conn = new PDO("mysql:host=$dbhost;dbname=$dbname;port=$dbport", $dbuser,$dbpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}  
catch(Exception $e)  
{   
	die("Cannot connect database.");
    print_r( $e->getMessage());
}

$config = [
    'settings' => [
        'displayErrorDetails' => DEBUG,

        'logger' => [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/app.log',
        ],
    ],
];

$app = new \Slim\App($config);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Mau ngapain?");
    return $response;
});
$app->get('/parties/{type}/{slug}',
    function (Request $request, Response $response) 
    use ($conn)
{
    $route = $request->getAttribute('route');

    $partyType = $route->getArgument('type');
    $partySlug = $route->getArgument('slug');

    $partyQ = $conn->prepare("select * from parties where `type` ='$partyType' and `slug`='$partySlug';");
    $partyQ->execute();
    $party = $partyQ->fetch(PDO::FETCH_ASSOC);

    if (!empty($party)) {
        return $response->withJson(['data'=>$party,'error'=>false], 200);
    } else {
        return $response->withJson(['message' => 'Woops!'], 404);
    }
});
$app->run();