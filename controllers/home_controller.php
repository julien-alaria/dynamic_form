<?php
// Contrôleur pour la page d'accueil

/**
 * Page d'accueil
 */
function home_index() {

    $books = get_all_books();
    $movies = get_all_movies();
    $video_games = get_all_video_games();

    $data = [
        'title' => 'Accueil',
        'message' => 'Bienvenue sur la Mediatheque!',
        'features' => [
            'Livres',
            'Films',
            'Jeux Vidéo',
        ],
        'books' => $books,
        'movies' => $movies,
        'video_games' => $video_games
    ];
    
    load_view_with_layout('home/index', $data);
}

/**
 * Page à propos
 */
function home_about() {
    $data = [
        'title' => 'À propos',
        'content' => 'Cette application est un starter kit PHP MVC développé avec une approche procédurale.'
    ];
    
    load_view_with_layout('home/about', $data);
}

/**
 * Page contact
 */
function home_contact() {
    $data = [
        'title' => 'Contact'
    ];
    
    if (is_post()) {
        $name = clean_input(post('name'));
        $email = clean_input(post('email'));
        $message = clean_input(post('message'));
        
        // Validation simple
        if (empty($name) || empty($email) || empty($message)) {
            set_flash('error', 'Tous les champs sont obligatoires.');
        } elseif (!validate_email($email)) {
            set_flash('error', 'Adresse email invalide.');
        } else {
            // Ici vous pourriez envoyer l'email ou sauvegarder en base
            set_flash('success', 'Votre message a été envoyé avec succès !');
            redirect('home/contact');
        }
    }
    
    load_view_with_layout('home/contact', $data);
} 


/**
 * Page profile
 */
function home_profile() {

    if (!is_logged_in()) {
    redirect('auth/login');
    }

    $data = [
        'title' => 'Profile',
        'message' => 'Bienvenue sur votre profil',
        'content' => 'Cette application est un starter kit PHP MVC développé avec une approche procédurale.'
    ];
    
    load_view_with_layout('home/profile', $data);
} 

/**
 * Page test
 */
function home_test() {
    $data = [
        'title' => 'Page test',
        'message' => 'Bienvenue sur votre page test',
    ];
    
    load_view_with_layout('home/test', $data);
} 