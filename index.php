<?php

session_start();
//-------------------
use \App\Autoloader;

// Appel des modèles
use \App\Model\Article;
use \App\Model\User;
use \App\Model\Categorie;

// Appel de la base de donnes
use \App\Repository\Database\Database;
use \App\Repository\DbRequest;

// Appel des contrôleurs
use \App\Controller\ArticleController;
use \App\Controller\UserController;


// AUTOLOADER PERSO
require 'App/Autoloader.php';
Autoloader::register();

//CONNEXION A LA BDD
$instanceDb = Database::getInstance();
$dbRequest = new DbRequest($instanceDb);

// CONTROLEURS
$articleController = new ArticleController($dbRequest);
$userController = new UserController($dbRequest);


// VENDOR AUTOLOADER
require 'vendor/autoload.php';

//TWIG TEMPLATE
$loader = new Twig_Loader_Filesystem('Views');
$twig = new Twig_Environment($loader, array(
    //'cache' => '/path/to/compilation_cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());
$twig->addExtension(new Twig_Extensions_Extension_Text());

//ROUTING
require_once 'Web/routeur.php';