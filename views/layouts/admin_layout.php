<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Admin'); ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/admin.css'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<header class="admin-header">
    <h1>Panneau d'administration</h1>
    <p>Connecté en tant que : <?= htmlspecialchars($_SESSION['user_name'] ?? ''); ?></p>
</header>

<?php flash_messages(); ?>

<aside class="side-bar" role="navigation" aria-label="Menu admin">
    <ul>
        <li><a href="<?= url('dashboard/index'); ?>">Tableau de bord</a></li>
        <li><a href="<?= url('dashboard/media_stats'); ?>">Gestion des médias</a></li>
        <li><a href="<?= url('dashboard/users'); ?>">Gestion des utilisateurs</a></li>
        <li><a href="<?= url('home/index'); ?>">Retour</a></li>
    </ul>
</aside>

<main class="admin-content" role="main">
    <?php if (!empty($message)) : ?>
        <p class="message"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?= $content ?? '' ?>
</main>

</body>
</html>
