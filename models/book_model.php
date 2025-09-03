<?php
// Modèle pour la table Livre

/**
 * Récupère tous les livres (non supprimés)
 */
function get_all_books($limit = null, $offset = 0) {
    $query = "SELECT books.*, genres.name AS genre_name FROM books JOIN genres ON books.genre_id = genres.id WHERE books.deleted_at is NULL ORDER BY books.title ASC";

    if ($limit !== null) {
        $query .= " LIMIT $offset, $limit";
    }

    return db_select($query);
}

/**
 * Récupère un livre par son ID
 */
function get_book_by_id($id) {
    $query = "SELECT books.*, genres.name AS genre_name FROM books JOIN genres ON books.genre_id = genres.id WHERE books.id = ? AND books.deleted_at is NULL";

    return db_select_one($query, [$id]);
}

/**
 * Crée un nouveau livre
 */
function create_book($title, $author, $isbn, $genre_id, $pages_nb, $summary, $publication_year, $image, $stock) {
    $query = "INSERT INTO books (title, author, isbn, genre_id, pages_nb, summary, publication_year, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    return db_execute($query, [$title, $author, $isbn, $genre_id, $pages_nb, $summary, $publication_year, $image, $stock]);
}

/**
 * Met à jour un livre
 */
function update_book($id, $title, $author, $isbn, $genre_id, $pages_nb, $summary, $publication_year, $image, $stock) {
    $query = "UPDATE books SET title = ?, author = ?, isbn = ?, genre_id = ?, pages_nb = ?, summary = ?, publication_year = ?, image = ?, stock = ? WHERE id = ?";

    return db_execute($query, [$title, $author, $isbn, $genre_id, $pages_nb, $summary, $publication_year, $image, $stock, $id]);
}

/**
 * Suppression d'un livre
 */
function delete_book($id) {
    $query = "UPDATE books SET deleted_at = NOW() WHERE id = ?";
    return db_execute($query, $id);
}

/**
 * Compte tous les livres actifs
 */
function count_books() {
    $query = "SELECT COUNT(*) as total FROM books WHERE deleted_at is NULL";
    $result = db_select_one($query);
    return $result['total'] ?? 0;
}