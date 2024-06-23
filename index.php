<?php
// === register autoloader
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});

$sp = new \ServiceProvider();

$sp->register(\Presentation\MVC\MVC::class, function(){
    return new \Presentation\MVC\MVC();
}, isSingleton: true);

// PRESENTATION
// controllers
$sp->register(\Presentation\Controllers\Home::class);
$sp->register(\Presentation\Controllers\Products::class);
$sp->register(\Presentation\Controllers\User::class);


// APPLICATION
// commands and querries
$sp->register(\Application\ProductsQuery::class);
$sp->register(\Application\ProductSearchQuery::class);
$sp->register(\Application\SignInCommand::class);
$sp->register(\Application\SignedInUserQuery::class);
$sp->register(\Application\SignOutCommand::class);
$sp->register(\Application\CheckIfUserExistsQuery::class);
$sp->register(\Application\SignUpCommand::class);
$sp->register(\Application\RatingsQuery::class);
$sp->register(\Application\RatingsChronoQuery::class);
$sp->register(\Application\ProductQuery::class);

$sp->register(\Application\Services\AuthenticationService::class);


// INFRASTRUCTURE
// sessions
$sp->register(\Infrastructure\Session::class, isSingleton: true);
$sp->register(\Application\Interfaces\Session::class, \Infrastructure\Session::class);

// repository
$sp->register(\Infrastructure\Repository::class, function() {
    return new \Infrastructure\Repository("localhost", "root", "", "produktbewertungsportal");
}, isSingleton: true);
$sp->register(\Application\Interfaces\ProductRepository::class, \Infrastructure\Repository::class);
$sp->register(\Application\Interfaces\UserRepository::class, \Infrastructure\Repository::class);
$sp->register(\Application\Interfaces\RatingsRepository::class, \Infrastructure\Repository::class);


$sp->resolve(\Presentation\MVC\MVC::class)->handleRequest($sp); 

