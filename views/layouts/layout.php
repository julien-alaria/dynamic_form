<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' - ' . APP_NAME : APP_NAME; ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css'); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <a href="<?= url(); ?>"><?= APP_NAME; ?></a>
            </div>
            <ul class="nav-menu">
                <li><a href="<?= url(); ?>">Accueil</a></li>
                <li><a href="<?= url('home/about'); ?>">A propos</a></li>
                <li><a href="<?= url('home/contact'); ?>">Contact</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="<?= url('auth/logout'); ?>">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= url('auth/login'); ?>">Connexion</a></li>
                    <li><a href="<?= url('auth/register'); ?>">Inscription</a></li>
                <li><a href="<?= url('auth/forgot-password2'); ?>">Mot de passe oublié</a></li>
                <?php endif; ?>
                <?php if (is_admin_or_modo()): ?>
                    <li><a href="<?= url('dashboard/index'); ?>" class="admin-button">Tableau de bord</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <?php flash_messages(); ?>
        <?php echo $content ?? ''; ?>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Tous droits réservés.</p>
            <p>Version <?php echo APP_VERSION; ?></p>
        </div>
    </footer>

    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>
</html>