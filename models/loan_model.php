<?php
// Modèle pour les emprunts (loan)

/**
 * Récupère tous les emprunts d'un utilisateur
 */
function get_loans_by_user($user_id) {
    $query = "SELECT *FROM loan WHERE user_id = ? ORDER BY loan_date DESC";
    return db_select($query, [$user_id]);
}

/**
 * Effectue un emprunt (si stock > 0)
 */
function create_loan($user_id, $media_type, $media_id, $loan_date, $expected_return_date) {

    // Valider le type de média pour éviter les injections
    $allowed_types = ['books', 'movies', 'video_games'];
    if (!in_array($media_type, $allowed_types)) {
        return false;
    }

    // Vérifier le stock
    if (!decrement_media_stock($media_type, $media_id)) {
        set_flash('erreur', 'stock insuffisant pour cet emprunt.');
        return false;
    }

    //Créer le prêt
    $query = "INSERT INTO loan (user_id, media_type, media_id, loan_date, expected_return_date, loan_status) VALUES (?, ?, ?, ?, ?, 'active')";

    return db_execute($query, [$user_id, $media_type, $media_id, $loan_date, $expected_return_date]);

}

/**
 * Retourné un emprunt
 */
function return_loan($loan_id) {
    $loan = db_select_one("SELECT media_type, media_id FROM loan WHERE id = ?", [$loan_id]);

    if (!$loan) {
        return false;
    }

    $query = "UPDATE loan SET actual_return_date = CURDATE(), loan_status = 'returned' WHERE id = ?";
    $success = db_execute($query, [$loan_id]);

    if ($success) {
        return increment_media_stock($loan['media_type'], $loan['media_id']);
    }

    return false;
}

/**
 * Fonction pour incrémenter le stock
 */
function increment_media_stock($media_type, $media_id) {
    $allowed_types = ['books', 'movies', 'video_games'];
    if (!in_array($media_type, $allowed_types)) {
        return false;
    }

    $query = "UPDATE {$media_type} SET stock = stock + 1 WHERE id = ?";
    return db_execute($query, [$media_id]);
}

/**
 * Fonction pour décrémenter le stock:
 */
function decrement_media_stock($media_type, $media_id) {
    $allowed_types = ['books', 'movies', 'video_games'];
    if (!in_array($media_type, $allowed_types)) {
        return false;
    }

    // Verifier les stocks
    $media = db_select_one("SELECT stock FROM {$media_type} WHERE id = ?", [$media_id]);
    if (!$media || $media['stock'] <= 0) {
        return false;
    }

    $query = "UPDATE {$media_type} SET stock = stock - 1 WHERE id = ?";
    return db_execute($query, [$media_id]);
}


