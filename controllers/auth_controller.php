<?php
// Contrôleur d'authentification

/**
 * Page de connexion
 */
function auth_login() {

    // Rediriger si déjà connecté
    if (is_logged_in()) {
        redirect('home');
    }
    
    $data = [
        'title' => 'Connexion'
    ];
    
    if (is_post()) {
        $email = clean_input(post('email'));
        $password = post('password');
        
        if (empty($email) || empty($password)) {
            set_flash('error', 'Email et mot de passe obligatoires.');
        } else {
            // Rechercher l'utilisateur
            $user = get_user_by_email($email);
   
            
            if ($user && verify_password($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                set_flash('success', 'Connexion réussie !');
                redirect('home');
            } else {
                set_flash('error', 'Email ou mot de passe incorrect.');
            }
        }
    }
    
    load_view_with_layout('auth/login', $data);
}

/**
 * Page d'inscription
 */
function auth_register() {
    // Rediriger si déjà connecté
    if (is_logged_in()) {
        redirect('home');
    }
    
    $data = [
        'title' => 'Inscription'
    ];
    
    if (is_post()) {
        $name = clean_input(post('name'));
        $lastname = clean_input(post('lastname'));
        $email = clean_input(post('email'));
        $password = post('password');
        $confirm_password = post('confirm_password');
        
        // Validation
        if (empty($name) || empty($lastname) || empty($email) || empty($password)) {
            set_flash('error', 'Tous les champs sont obligatoires.');
        } elseif (!validate_email($email)) {
            set_flash('error', 'Adresse email invalide.');
        } elseif (strlen($password) < 6) {
            set_flash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
        } elseif ($password !== $confirm_password) {
            set_flash('error', 'Les mots de passe ne correspondent pas.');
        } elseif (get_user_by_email($email)) {
            set_flash('error', 'Cette adresse email est déjà utilisée.');
        } else {
            // Créer l'utilisateur
            $user_id = create_user($name, $lastname, $email, $password);
            
            if ($user_id) {
                set_flash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                redirect('auth/login');
            } else {
                set_flash('error', 'Erreur lors de l\'inscription.');
            }
        }
    }
    
    load_view_with_layout('auth/register', $data);
}

/**
 * Déconnexion
 */
function auth_logout() {
    logout();
} 