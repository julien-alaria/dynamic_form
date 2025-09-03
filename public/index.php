<?php
/**
 * Point d'entrée principal de l'application PHP MVC
 * 
 * Ce fichier initialise l'application et lance le système de routing
 */

// require_once '../bootstrap.php';

// Démarrer la session
session_start();

// Charger la configuration
require_once '../config/database.php';

// Charger les fichiers core
require_once CORE_PATH . '/database.php';
require_once CORE_PATH . '/router.php';
require_once CORE_PATH . '/view.php';

// Charger les fichiers utilitaires
require_once INCLUDE_PATH . '/helpers.php';

// Charger les modèles
require_once MODEL_PATH . '/user_model.php';
require_once MODEL_PATH . '/book_model.php';
require_once MODEL_PATH . '/movie_model.php';
require_once MODEL_PATH . '/video_game_model.php';
require_once MODEL_PATH . '/loan_model.php';
require_once MODEL_PATH . '/dashboard_model.php';
require_once MODEL_PATH . '/genre_model.php';

// Activer l'affichage des erreurs en développement
// À désactiver en production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Lancer le système de routing
dispatch(); 