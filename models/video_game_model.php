<?php
// Modèle pour les jeux video

/**
 * Récupère tous les jeux vidéo (non supprimés)
 */
function get_all_video_games($limit = null, $offset = 0) {
    $query = "SELECT video_games.*, genres.name AS genre_name FROM video_games JOIN genres ON video_games.genre_id = genres.id WHERE video_games.deleted_at IS NULL ORDER BY video_games.title ASC";

    if ($limit !== null) {
        $query .= " LIMIT $offset, $limit";
    }

    return db_select($query);
}

/**
 * Récupère un jeu vidéo par son ID
 */
function get_video_game_by_id($id) {
    $query = "SELECT video_games.*, genres.name AS genre_name FROM video_games JOIN genres ON video_games.genre_id = genres.id WHERE video_games.id = ? AND video_games.deleted_at IS NULL";

    return db_select_one($query, [$id]);
}

/**
 * Crée un nouveau jeu_video
 */
function create_video_game($title, $publisher, $platform, $genre_id, $minimum_age, $description, $image, $stock) {
    $query = "INSERT INTO video_games (title, publisher, platform, genre_id, minimum_age, description, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    return db_execute($query, [$title, $publisher, $platform, $genre_id, $minimum_age, $description, $image, $stock]);
}

/**
 * Met à jour un jeu vidéo
 */
function update_video_game($id, $title, $publisher, $platform, $genre_id, $minimum_age, $description, $image, $stock) {
    $query = "UPDATE video_games SET title = ?, publisher = ?, platform = ?, genre_id = ?, minimum_age = ?, description = ?, image = ?, stock = ? WHERE id = ?";

    return db_execute($query, [$title, $publisher, $platform, $genre_id, $minimum_age, $description, $image, $stock, $id]);

}

/**
 * Suppression d'un jeu video
 */
function delete_video_game($id) {
    $query = "UPDATE video_games SET deleted_at = NOW() WHERE id = ?";
    return db_execute($query, [$id]);
}

/**
 * Compte tous les jeux vidéo actifs
 */
function count_video_games() {
    $query = "SELECT COUNT(*) as total FROM video_games WHERE deleted_at IS NULL";
    $result = db_select_one($query);
    return $result['total'] ?? 0;
}