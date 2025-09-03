<?php
// Fonctions utilitaires

/**
 * Sécurise l'affichage d'une chaîne de caractères (protection XSS)
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Affiche une chaîne sécurisée (échappée)
 */
function e($string) {
    echo escape($string);
}

/**
 * Retourne une chaîne sécurisée sans l'afficher
 */
function esc($string) {
    return escape($string);
}

/**
 * Génère une URL absolue
 */
function url($path = '') {
    $base_url = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');
    return $base_url . '/' . $path;
}

/**
 * Redirection HTTP
 */
function redirect($path = '') {
    $url = url($path);
    header("Location: $url");
    exit;
}

/**
 * Génère un token CSRF
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie un token CSRF
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Définit un message flash
 */
function set_flash($type, $message) {
    $_SESSION['flash_messages'][$type][] = $message;
}

/**
 * Récupère et supprime les messages flash
 */
function get_flash_messages($type = null) {
    if (!isset($_SESSION['flash_messages'])) {
        return [];
    }

    if ($type) {
        $messages = $_SESSION['flash_messages'][$type] ?? [];
        unset($_SESSION['flash_messages'][$type]);
        return $messages;
    }

    $messages = $_SESSION['flash_messages'];
    unset($_SESSION['flash_messages']);
    return $messages;
}

/**
 * Vérifie s'il y a des messages flash
 */
function has_flash_messages($type = null) {
    if (!isset($_SESSION['flash_messages'])) {
        return false;
    }

    if ($type) {
        return !empty($_SESSION['flash_messages'][$type]);
    }

    return !empty($_SESSION['flash_messages']);
}

/**
 * Nettoie une chaîne de caractères
 */
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Valide une adresse email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Génère un mot de passe sécurisé
 */
function generate_password($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

/**
 * Hache un mot de passe
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Vérifie un mot de passe
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Formate une date
 */
function format_date($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}

/**
 * Vérifie si une requête est en POST
 */
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Vérifie si une requête est en GET
 */
function is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Retourne la valeur d'un paramètre POST
 */
function post($key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Retourne la valeur d'un paramètre GET
 */
function get($key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Vérifie si un utilisateur est connecté
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Retourne l'ID de l'utilisateur connecté
 */
function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Déconnecte l'utilisateur
 */
function logout() {
    session_destroy();
    redirect('auth/login');
}

/**
 * Formate un nombre
 */
function format_number($number, $decimals = 2) {
    return number_format($number, $decimals, ',', ' ');
}

/**
 * Génère un slug à partir d'une chaîne
 */
function generate_slug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
} 

/**
 * Vérifie si l'utilisateur connecté est admin
 */
function is_admin() {
    return is_logged_in() && get_user_role(current_user_id()) === 'admin';
}

/**
 * Empêche l'accès si l'utilisateur n'est pas admin
 */
function require_admin() {
    if (!is_admin()) {
        set_flash('error', 'Accès refusé.');
        redirect('login');
        exit;
    }
}

/**
 * Vérifie si l'utilisateur connecté a le rôle 'admin' ou 'moderator' et retourne true.
 */
function is_admin_or_modo() {
    if (!is_logged_in()) {
        return false;
    }
    $role = get_user_role(current_user_id());
    return in_array($role, ['admin', 'moderator']);
}

/**
 * Gère l'upload d'une image de couverture
 */
function handle_image_upload(): ?string {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmp_name = $_FILES['image']['tmp_name'];
    $original_name = $_FILES['image']['name'];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    if (!validate_image_extension($extension)) {
        return null;
    }

    if (!validate_image_size($_FILES['image']['size'])) {
        return null;
    }

    if (!validate_image_mime($tmp_name)) {
        return null;
    }

    if (!validate_image_dimensions($tmp_name)) {
        return null;
    }

    $upload_dir = get_or_create_upload_dir();

    // Générer un nom unique
    $filename = uniqid('cover_', true) . '.' . $extension;
    $destination = $upload_dir . $filename;

    // Déplacement du fichier
    if (!move_uploaded_file($tmp_name, $destination)) {
        set_flash('error', 'Erreur lors du déplacement de l\'image.');
        error_log("move_uploaded_file failed: tmp=$tmp_name dest=$destination");
        return null;
    }

    set_flash('success', 'Image uploadée avec succès.');
    return $filename;
}

/**
 * Création de dossier
 */
function get_or_create_upload_dir(): string {
    $upload_dir = __DIR__ . '/../public/uploads/covers/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    return $upload_dir;
}

/**
 * Vérification de la taille de l'image
 */
function validate_image_size(int $size): bool {
    $max_size = 2 * 1024 * 1024; // 2 Mo
    if ($size > $max_size) {
        set_flash('error', 'L\'image dépasse la taille maximale de 2 Mo.');
        return false;
    }
    return true;
}

/**
 * Vérification de type MIME
 */
function validate_image_mime(string $tmp_name): bool {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = mime_content_type($tmp_name);

    if (!in_array($file_type, $allowed_types)) {
        set_flash('error', 'Format d\'image non autorisé. Formats acceptés : JPG, PNG, GIF.');
        return false;
    }
    return true;
}

/**
 * Vérification  des dimensions
 */
function validate_image_dimensions(string $tmp_name): bool {
    [$width, $height] = getimagesize($tmp_name);
    if ($width !== 300 || $height !== 400) {
        set_flash('error', 'L\'image doit faire exactement 300 x 400 pixels.');
        return false;
    }
    return true;
}

/**
 * Vérification de l'extension
 */
function validate_image_extension(string $extension): bool {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extension, $allowed_extensions)) {
        set_flash('error', 'Extension de fichier non autorisée.');
        return false;
    }
    return true;
}
