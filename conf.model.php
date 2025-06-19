<?php 

// Modèle de fichier de configuration. A adapter selon l'environnement et renommer en conf.php

// Reporting d'erreurs 
// Dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Prod
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(-1);

// Infos de connexion à la base de données (à renseigner)
define("DB_NAME", "");
define("DB_USER", "");
define("DB_PASSWORD", "");
define("DB_HOST", "");

// Chemins de fichier
define("ROOT_DIR", __DIR__);
define("ROOT_HTML", "");

// Class autoloader
spl_autoload_register(function ($class_name) {
    require ROOT_DIR . "/models/" . strtolower($class_name) . '.php';
});

// Démarrage de la session
session_start();