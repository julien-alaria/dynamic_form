<?php

// Controller pour le dashboard admin

/**
 * Page d'accueil
 */
function dashboard_index() {
    require_admin();

    // obtenir le role de la session
    $role = $_SESSION['role'] ?? 'guest';

    $totalUsers = get_all_users_number();
    $totalMedia = get_all_media_number();
    $totalMovies = get_media_movie_number();
    $totalBooks = get_media_book_number();
    $totalVideoGames = get_media_videogame_number();
    $totalLoans = get_all_loans_number();
    $totalLateLoans = get_all_late_loans_number();

    $data = [
        'title' => 'Tableau de bord',
        'user_id' => $_SESSION['user_id'],
        'role' => $role,
        'totalUsers' => $totalUsers,
        'totalMedia' => $totalMedia,
        'totalMovies' => $totalMovies,
        'totalBooks' => $totalBooks,
        'totalVideoGames' => $totalVideoGames,
        'totalLoans' => $totalLoans,
        'totalLateLoans' => $totalLateLoans
    ];

    load_view_with_layout('dashboard/index', $data, 'admin_layout');
}

/**
 * Page Gestion des utilisateurs
 */
function dashboard_users() {
    require_admin();

    $edit_user = null;

    if (is_post()) {
        if (isset($_POST['create_user'])) {
            handle_user_creation();
        } elseif (isset($_POST['update_user'])) {
            handle_user_update();
        } elseif (isset($_POST['delete_user'])) {
            handle_delete_user();
        } elseif (isset($_POST['admin_update'])) {
            $user_id =post('user_id');
            $edit_user = get_user_by_id($user_id);
        }
    }

    $users = get_all_users();

    $data = [
        'title' => 'Gestion des utilisateurs',
        'users' => $users,
        'edit_user' => $edit_user
    ];

    load_view_with_layout('dashboard/users', $data, 'admin_layout');
}

/**
 * récupération infos & création utilisateur
 */
function handle_user_creation() {

    if (!verify_csrf_token(post('csrf_token'))) {
    set_flash('error', 'Token CSRF invalide.');
    redirect('dashboard/users');
    }

    $name = clean_input(post('name'));
    $lastname = clean_input(post('lastname'));
    $email = clean_input(post('email'));
    $password = post('password');
    $confirm_password = post('confirm_password');
    $role = post('role') ?? 'standard';

    $result = register_user($name, $lastname, $email, $password, $confirm_password, $role);

    if (isset($result['error'])) {
        set_flash('error', $result['error']);
        redirect('dashboard/users');
    } else {
        set_flash('success', 'Utilisateur créé avec succès.');
        redirect('/dashboard/users');
    }
}

/**
 * Mise à jour utilisateur
 */
function handle_user_update() {

    if (!verify_csrf_token(post('csrf_token'))) {
    set_flash('error', 'Token CSRF invalide.');
    redirect('dashboard/users');
    }

    $id = (int) post('user_id');
    $name = clean_input(post('name'));
    $lastname = clean_input(post('lastname'));
    $email = clean_input(post('email'));
    $role = post('role') ?? 'standard';

    if (empty($name) || empty($lastname) || empty($email)) {
        set_flash('error', 'Tous les champs sont requis pour la modification.');
    } else {
        if (update_user($id, $name, $lastname, $email, $role)) {
            set_flash('success', 'Utilisateur mis à jour avec succès.');
        } else {
            set_flash('error', 'Erreur lors de la mise à jour.');
        }
    }
}

/**
 * enregistrement utilisateur en base de donnée
 */
function register_user($name, $lastname, $email, $password, $confirm_password, $role = 'standard') {
    if (empty($name) || empty($lastname) || empty($email) || empty($password)) {
        return ['error' => 'Tous les champs sont obligatoires.'];
    }

    if (!validate_email($email)) {
        return ['error' => 'Adresse email invalide.'];
    }

    if (strlen($password) < 6) {
        return ['error' => 'Le mot de passe doit contenir au moins 6 caractères.'];
    }

    if ($password !== $confirm_password) {
        return ['error' => 'Les mots de passe ne correspondent pas.'];
    }

    if (get_user_by_email($email)) {
        return ['error' => 'Cette adresse email est déjà utilisée.'];
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $user_id = create_user($name, $lastname, $email, $hashedPassword, $role);

    if (!$user_id) {
        return ['error' => 'Erreur lors de la création de l\'utilisateur.'];
    }

    return ['success' => 'Utilisateur créé avec succès.', 'user_id' => $user_id];
}

/**
 * Suppression d'utilisateur
 */
function handle_delete_user() {
    $id = post('user_id');

    if (delete_user($id)) {
        set_flash('success', 'Utilisateur supprimé avec succès.');
    } else {
        set_flash('error', 'Erreur lors de la suppression de l\'utilisateur.');
    }

    redirect('dashboard/users');
}

/**
 * Page de Gestion des médias
 */
function dashboard_media_stats() {
    require_admin();

    $edit_book = null;
    $edit_movie = null;
    $edit_video_game = null;

    route_media_actions($edit_book, $edit_movie, $edit_video_game);

    $books = get_all_books();
    $movies = get_all_movies();
    $video_games = get_all_video_games();
    $genres = get_all_genres();

    $media_type = $_SESSION['media_type'] ?? null;


    $data = [
        'title' => 'Gestion des médias',
        'books' => $books,
        'movies' => $movies,
        'video_games' => $video_games,
        'genres' => $genres,
        'edit_book' => $edit_book,
        'edit_movie' => $edit_movie,
        'media_type' => $media_type,
        'message' => 'Liste des médias enregistrés'
    ];

    load_view_with_layout('dashboard/media_stats', $data, 'admin_layout');
}

/**
 * Création de livres
 */
function dashboard_books_stats() {
   if (is_post() && post('media-type') === 'book') {

    $title = clean_input(post('title'));
    $author = clean_input(post('author'));
    $isbn = clean_input(post('isbn'));
    $genre_id = (int) clean_input(post('genre_id'));
    $pages_nb = (int) clean_input(post('pages_nb'));
    $summary = clean_input(post('summary'));
    $publication_year = (int) clean_input(post('publication_year')); 
    $stock = (int) clean_input(post('stock'));

    $image = handle_image_upload() ?? 'default.jpg';

    if (empty($title) || empty($author) || empty($isbn) || empty($genre_id) || empty($pages_nb) || empty($summary) || empty($publication_year) || empty($stock)) {
        set_flash('error', 'Tous les champs sont obligatoires');
        } else {
            $add_book = create_book($title, $author, $isbn, $genre_id, $pages_nb, $summary, $publication_year, $image, $stock);
            if ($add_book) {
                set_flash('success', 'Livre créé avec succès');
            } else {
                set_flash('error', 'Erreur lors de la création');
            }
        }
    }
}

/**
 * Update de livres
 */
function dashboard_books_update() {

    if (is_post() && post('media-type') === 'book') {

    $id = (int) clean_input(post('book_id'));
    $title = clean_input(post('title'));
    $author = clean_input(post('author'));
    $isbn = clean_input(post('isbn'));
    $genre_id = (int) clean_input(post('genre_id'));
    $pages_nb = (int) clean_input(post('pages_nb'));
    $summary = clean_input(post('summary'));
    $publication_year = (int) clean_input(post('publication_year')); 
    $stock = (int) clean_input(post('stock'));

    $image = handle_image_upload() ?? 'default.jpg';

    if (empty($title) || empty($author) || empty($isbn) || empty($genre_id) || empty($pages_nb) || empty($summary) || empty($publication_year) || empty($stock)) {
        set_flash('error', 'Tous les champs sont obligatoires');
        } else {
            $add_book = update_book($id, $title, $author, $isbn, $genre_id, $pages_nb, $summary, $publication_year, $image, $stock);
            if ($add_book) {
                set_flash('success', 'Livre mis à avec succès');
            } else {
                set_flash('error', 'Erreur lors de la mise à jour');
            }
        }
    }
}

/**
 * Delete de livres
 */
function dashboard_books_delete() {

    if (is_post('media-type') === 'delete_book') {
        $id = (int) clean_input(post('book_id'));

        $delete_book = delete_book($id); 
    } if ($delete_book) {
        set_flash('success', 'Livre supprimé de la base de donnée');
    } else {
        set_flash('error', 'Erreur lors de la suppression du media.');
    }
}

/**
 * Création de films
 */
function dashboard_movies_stats() {
    if (is_post() && post('media-type') === 'movie') {

    $title = clean_input(post('title'));
    $director = clean_input(post('director'));
    $year = (int) clean_input(post('year'));
    $genre_id = (int) clean_input(post('genre_id'));
    $duration = (int) clean_input(post('duration'));
    $synopsis = clean_input(post('synopsis'));
    $classification = clean_input(post('classification'));
    $stock = (int) clean_input(post('stock'));

    $image = handle_image_upload() ?? 'default.jpg';

    $valid_classifications = ['tous_publics', '12', '16', '18'];

    if (!in_array($classification, $valid_classifications)) {
        set_flash('error', 'Classification invalide.');
        return;
    }

    if (empty($title) || empty($director) || empty($year) || empty($duration) || empty($synopsis) || empty($classification) || empty($stock)) {
        set_flash('error', 'Tous les champs sont obligatoires');
        } else {
            $add_movie = create_movie($title, $genre_id, $director, $year, $duration, $synopsis, $classification, $image, $stock);
            if ($add_movie) {
                set_flash('success', 'Film créé avec succès');
            } else {
                set_flash('error', 'Erreur lors de la création');
            }
        }
    }
}

/**
 * Update de films
 */
function dashboard_movies_update() {
    if (is_post() && post('media-type') === 'movie') {

    $id = (int) clean_input(post('movie_id'));
    $title = clean_input(post('title'));
    $director = clean_input(post('director'));
    $year = (int) clean_input(post('year'));
    $genre_id = (int) clean_input(post('genre_id'));
    $duration = (int) clean_input(post('duration'));
    $synopsis = clean_input(post('synopsis'));
    $classification = clean_input(post('classification'));
    $stock = (int) clean_input(post('stock'));

    $image = handle_image_upload() ?? 'default.jpg';

    $valid_classifications = ['tous_publics', '12', '16', '18'];

    if (!in_array($classification, $valid_classifications)) {
        set_flash('error', 'Classification invalide.');
        return;
    }

    if (empty($title) || empty($director) || empty($year) || empty($duration) || empty($synopsis) || empty($classification) || empty($stock)) {
        set_flash('error', 'Tous les champs sont obligatoires');
        } else {
            $add_movie = update_movie($id, $title, $genre_id, $director, $year, $duration, $synopsis, $classification, $image, $stock);
            if ($add_movie) {
                set_flash('success', 'Film mis à jour avec succès');
            } else {
                set_flash('error', 'Erreur lors de la mise à jour');
            }
        }
    }
}

/**
 * Delete de films
 */
function dashboard_movies_delete() {

    if (is_post('media-type') === 'delete_movie') {
        $id = (int) clean_input(post('movie_id'));

        $delete_movie = delete_movie($id); 
    } if ($delete_movie) {
        set_flash('success', 'Media supprimé de la base de donnée');
    } else {
        set_flash('error', 'Erreur lors de la suppression du media.');
    }
}

/**
 * Création de jeux vidéos
 */
function dashboard_video_games_stats() {
    if (is_post() && post('media-type') === 'video_game') {

    $title = clean_input(post('title'));
    $publisher = clean_input(post('publisher'));
    $platform = clean_input(post('platform'));
    $genre_id = (int) clean_input(post('genre_id'));
    $minimum_age = clean_input(post('minimum_age'));
    $description = clean_input(post('description'));
    $stock = (int) clean_input(post('stock'));

    $image = handle_image_upload() ?? 'default.jpg';

    $valid_ages = ['3','7','12','16','18'];
    $valid_platforms = ['PC','Playstation','Xbox','Nintendo','Mobile'];

    if (!in_array($minimum_age, $valid_ages)) {
        set_flash('error', 'Âge minimum invalide.');
        return;
    }

    if (!in_array($platform, $valid_platforms)) {
        set_flash('error', 'Plateforme invalide.');
        return;
    }

    if (empty($title) || empty($publisher) || empty($platform) || empty($genre_id) || empty($minimum_age) || empty($description) || empty($stock)) {
        set_flash('error', 'Tous les champs sont obligatoires');
        } else {
            $add_video_game = create_video_game($title, $publisher, $platform, $genre_id, $minimum_age, $description, $image, $stock);
            if ($add_video_game) {
                set_flash('success', 'Jeu vidéo créé avec succès');
            } else {
                set_flash('error', 'Erreur lors de la création');
            }
        }
    }
}

/**
 * Update de jeux video
 */
function dashboard_video_games_update() {
    if (is_post() && post('media-type') === 'video_game') {

        $id = isset($_POST['video_game_id']) ? (int) clean_input($_POST['video_game_id']) : null;

        $title = clean_input(post('title'));
        $publisher = clean_input(post('publisher'));
        $platform = clean_input(post('platform'));
        $genre_id = (int) clean_input(post('genre_id'));
        $minimum_age = clean_input(post('minimum_age'));
        $description = clean_input(post('description'));
        $stock = (int) clean_input(post('stock'));

        $image = handle_image_upload() ?? 'default.jpg';

        $valid_ages = ['3','7','12','16','18'];
        $valid_platforms = ['PC','Playstation','Xbox','Nintendo','Mobile'];

        if (!in_array($minimum_age, $valid_ages)) {
            set_flash('error', 'Âge minimum invalide.');
            return;
        }

        if (!in_array($platform, $valid_platforms)) {
            set_flash('error', 'Plateforme invalide.');
            return;
        }

        if (empty($title) || empty($publisher) || empty($platform) || empty($genre_id) || empty($minimum_age) || empty($description) || empty($stock)) {
            set_flash('error', 'Tous les champs sont obligatoires.');
            return;
        }

        // 🟢 On met à jour si un ID est fourni
        if ($id !== null) {
            $updated = update_video_game($id, $title, $publisher, $platform, $genre_id, $minimum_age, $description, $image, $stock);
            if ($updated) {
                set_flash('success', 'Jeu vidéo modifié avec succès');
            } else {
                set_flash('error', 'Erreur lors de la modification');
            }
        } else {
            set_flash('error', 'ID du jeu vidéo manquant.');
        }
    }
}

/**
 * Delete de jeux video
 */
function dashboard_video_games_delete() {

    if (is_post('media-type') === 'delete_video_game') {
        $id = (int) clean_input(post('video_game_id'));

        $delete_movie = delete_video_game($id); 
    } if ($delete_video_game) {
        set_flash('success', 'Media supprimé de la base de donnée');
    } else {
        set_flash('error', 'Erreur lors de la suppression du media.');
    } 
}

/**
 * Switch pour gestion d'affichage formulaires
 */
function render_form($media_type, $genres, $edit_data = null) {
    switch($media_type) {
        case 'book':
            render_book_form($genres, $edit_data);
            break;
        case 'movie':
            render_movie_form($genres, $edit_data);
            break;
        case 'video_game':
            render_video_game_form($genres, $edit_data);
            break;
        default:
            echo "<p>Sélection du type de média.</p>";
    }
}

/**
 * Router pour les actions admin sur les medias (utilisé dans dashboard_media_stats)
 */
function route_media_actions(&$edit_book, &$edit_movie, &$edit_video_game) {
    if (isset($_POST['media_type'])) {
        $_SESSION['media_type'] = $_POST['media_type'];
    }

    $media_type = $_SESSION['media_type'] ?? null;

    if (!is_post() || !$media_type) {
        return;
    }

    switch ($media_type) {
        case 'book':
            if (isset($_POST['create_book'])) {
                dashboard_books_stats();
            } elseif (isset($_POST['update_book'])) {
                dashboard_books_update();
            } elseif (isset($_POST['delete_book'])) {
                dashboard_books_delete();
            } elseif (isset($_POST['admin_update']) && post('book_id')) {
                $edit_book = get_book_by_id(post('book_id'));
            }
            break;

        case 'movie':
            if (isset($_POST['create_movie'])) {
                dashboard_movies_stats();
            } elseif (isset($_POST['update_movie'])) {
                dashboard_movies_update();
            } elseif (isset($_POST['delete_movie'])) {
                dashboard_movies_delete();
            } elseif (isset($_POST['admin_update']) && post('movie_id')) {
                $edit_movie = get_movie_by_id(post('movie_id'));
            }
            break;
        
            case 'video_game':
                if (isset($_POST['create_video_game'])) {
                    dashboard_video_games_stats();
                } elseif (isset($_POST['update_video_game'])) {
                    dashboard_video_games_update();
                } elseif (isset($_POST['delete_video_game'])) {
                    dashboard_video_games_delete();
                } elseif (isset($_POST['admin_update']) && post('video_game_id')) {
                    $edit_video_game = get_video_game_by_id('video_game_id');
                }
            break;

        default:
            set_flash('error', 'Type de média inconnu.');
    }
}

