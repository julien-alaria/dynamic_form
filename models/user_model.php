<?php
// Modèle pour les utilisateurs

/**
 * Récupère un utilisateur par son email
 */
function get_user_by_email($email) {
    $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
    return db_select_one($query, [$email]);
}

/**
 * Récupère un utilisateur par son ID
 */
function get_user_by_id($id) {
    $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
    return db_select_one($query, [$id]);
}

/**
 * Crée un nouvel utilisateur
 */
function create_user($name, $lastname, $email, $password) {
    $hashed_password = hash_password($password);
    $query = "INSERT INTO users (name, lastname, email, password, creation_date) VALUES (?, ?, ?, ?, NOW())";
    
    if (db_execute($query, [$name, $lastname, $email, $hashed_password])) {
        return db_last_insert_id();
    }
    
    return false;
}

/**
 * Met à jour un utilisateur
 */
function update_user($id, $name, $lastname, $email, $role = 'standard') {
    $query = "UPDATE users SET name = ?, lastname = ?, email = ?, role = ? WHERE id = ?";
    return db_execute($query, [$name, $lastname, $email, $role, $id]);
}

/**
 * Met à jour le mot de passe d'un utilisateur
 */
function update_user_password($id, $password) {
    $hashed_password = hash_password($password);
    $query = "UPDATE users SET password = ? WHERE id = ?";
    return db_execute($query, [$hashed_password, $id]);
}

/**
 * Supprime un utilisateur
 */
function delete_user($id) {
    $query = "DELETE FROM users WHERE id = ?";
    return db_execute($query, [$id]);
}

/**
 * Récupère tous les utilisateurs
 */
function get_all_users($limit = null, $offset = 0) {
    $query = "SELECT id, name, lastname, email, role, creation_date FROM users ORDER BY creation_date DESC";
    
    if ($limit !== null) {
        $query .= " LIMIT $offset, $limit";
    }
    
    return db_select($query);
}

/**
 * Compte le nombre total d'utilisateurs
 */
function count_users() {
    $query = "SELECT COUNT(*) as total FROM users";
    $result = db_select_one($query);
    return $result['total'] ?? 0;
}

/**
 * Vérifie si un email existe déjà
 */
function email_exists($email, $exclude_id = null) {
    $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
    $params = [$email];
    
    if ($exclude_id) {
        $query .= " AND id != ?";
        $params[] = $exclude_id;
    }
    
    $result = db_select_one($query, $params);
    return $result['count'] > 0;
} 

/**
 * Retourne le rôle d'un utilisateur
 */
function get_user_role($id) {
    $query = "SELECT role FROM users WHERE id = ?";
    $result = db_select_one($query, [$id]);
    return $result['role'] ?? null;
}

/**
 * Retourne toutes les infos de l'utilisateur connecté
 */
function current_user() {
    $id = current_user_id();
    return $id ? get_user_by_id($id) : null;
}