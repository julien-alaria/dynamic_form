<?php
// Model pour le dashboard admin

// Récupérer le nombre total d'utilisateurs
function get_all_users_number() {
    $query = "SELECT COUNT(*) as total_users FROM users";

    return (int) (db_select_one($query)['total_users'] ?? 0);
}

// Récupérer le nombre total de médias (livres/films/Jeux vidéo)
function get_all_media_number() {
    $query = "SELECT (
                (SELECT COUNT(*) FROM books WHERE deleted_at IS NULL) +
                (SELECT COUNT(*) FROM movies WHERE deleted_at IS NULL) +
                (SELECT COUNT(*) FROM video_games WHERE deleted_at IS NULL)
            ) as total_media
    ";

    return (int) (db_select_one($query)['total_media'] ?? 0);
}

// Récupérer le nombre de films
function get_media_movie_number() {
    $query = "SELECT COUNT(*) AS total_movies FROM movies WHERE deleted_at IS NULL";

    return (int) (db_select_one($query)['total_movies'] ?? 0);
}

// Récupérer le nombre de livres
function get_media_book_number() {
    $query = "SELECT COUNT(*) AS total_books FROM books WHERE deleted_at is NULL";

    return (int) (db_select_one($query)['total_books'] ?? 0);
}

// Récupérer le nombre de jeux vidéos
function get_media_videogame_number() {
    $query = "SELECT COUNT(*) AS total_video_games FROM video_games WHERE deleted_at IS NULL";

    return (int) (db_select_one($query)['total_video_games'] ?? 0);
}

// Récuperer le nombre de prêts en cours
function get_all_loans_number() {
    $query = "SELECT COUNT(*) as total_loans FROM loan WHERE actual_return_date IS NULL";

    return (int) (db_select_one($query)['total_loans'] ?? 0);
}

// Récuperer le nombre de prêts en retard
function get_all_late_loans_number() {
    $query = " SELECT COUNT(*) as total_late_loans FROM loan WHERE actual_return_date IS NULL AND expected_return_date < CURDATE()";

    return (int) (db_select_one($query)['total_late_loans'] ?? 0);
}
