<?php
// Modèle pour la table movies

/**
 * Récupère tous les films (non supprimés)
 */
function get_all_movies($limit = null, $offset = 0) {
    $query = "SELECT movies.*, genres.name AS genre_name FROM movies JOIN genres ON movies.genre_id = genres.id WHERE movies.deleted_at IS NULL ORDER BY movies.title ASC";

    if ($limit !== null) {
        $query .= " LIMIT $offset, $limit";
    }

    return db_select($query);
}

/**
 * Récupère un film par son ID
 */
function get_movie_by_id($id) {
    $query = "SELECT movies.*, genres.name AS genre_name FROM movies JOIN genres ON movies.genre_id = genres.id WHERE movies.id = ? AND movies.deleted_at is NULL";

    return db_select_one($query, [$id]);
}

/**
 * Crée un nouveau film
 */
function create_movie($title, $genre_id, $director, $year, $duration, $synopsis, $classification, $image, $stock) {
    $query = "INSERT INTO movies (title, genre_id, director, year, duration, synopsis, classification, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    return db_execute($query, [$title, $genre_id, $director, $year, $duration, $synopsis, $classification, $image, $stock]);
}

/**
 * Met à jour un film
 */
function update_movie($id, $title, $genre_id, $director, $year, $duration, $synopsis, $classification, $image, $stock) {
    $query = "UPDATE movies SET title = ?, genre_id = ?, director = ?, year = ?, duration = ?, synopsis = ?, classification = ?, image = ?, stock = ? WHERE id = ?";

    return db_execute($query, [$title, $genre_id, $director, $year, $duration, $synopsis, $classification, $image, $stock, $id]);
}

/**
 * Suppression d'un film
 */
function delete_movie($id) {
    $query = "UPDATE movies set deleted_at = NOW() WHERE id = ?";
    return db_execute($query, [$id]);
}

/**
 * Compte tous les films actifs
 */
function count_movies() {
    $query = "SELECT COUNT(*) as total FROM movies WHERE deleted_at is NULL";
    $result = db_select_one($query);
    return $result['total'] ?? 0;
}