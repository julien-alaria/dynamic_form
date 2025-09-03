<div class="hero">
    <div class="hero-content">
        <h1><?php e($message); ?></h1>
        <p class="hero-subtitle">Mediatheque</p>
        <?php if (!is_logged_in()): ?>
            <div class="hero-buttons">
                <a href="<?php echo url('auth/register'); ?>" class="btn btn-primary">Commencer</a>
                <a href="<?php echo url('auth/login'); ?>" class="btn btn-secondary">Se connecter</a>
            </div>
        <?php else: ?>
            <p class="welcome-message">
                <i class="fas fa-user"></i> 
                Bienvenue, <?php e($_SESSION['user_name']); ?> !
            </p>
        <?php endif; ?>
    </div>
</div>

<section class="features">
    <div class="container">
        <h2>Medias disponibles à la location</h2>
        <div class="features-grid">
            <?php foreach ($features as $feature): ?>
                <div class="feature-card">
                    <i class="fas fa-check-circle"></i>
                    <h3><?php e($feature); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="media_books">
    <h2>Livres disponibles</h2>
    <div class="cards-container">
        <?php foreach($books as $book): ?>
            <div class="card">
                <img src="images/books/<?= escape($book['image'] ?: 'default.jpg') ?>" alt="">
                <div class="card-content">
                    <h3><?= escape($book['title']) ?></h3>
                    <p>Auteur: <?= escape($book['author']) ?></p>
                    <p>Pages : <?= escape($book['pages_nb']) ?></p>
                    <p><?= escape($book['summary']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="media_movies">
    <h2>Films disponibles</h2>
    <div class="cards-container">
        <?php foreach($movies as $movie): ?>
            <div class="card">
                <img src="images/cards/<?= escape($movie['image'] ?: 'default.jpg') ?>" alt="">
                <div class="card-content">
                    <h3><?= escape($movie['title']) ?></h3>
                    <p>Réalisateur: <?= escape($movie['director']) ?></p>
                    <p>Année: <?= escape($movie['year']) ?></p>
                    <p>Classification: <?= escape($movie['classification']) ?></p>
                    <p><?= escape($movie['synopsis']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="media_video_games">
    <h2>Jeux vidéo disponibles</h2>
    <div class="cards-container">
        <?php foreach($video_games as $game): ?>
            <div class="card">
                <img src="images/video_games/<?= escape($game['image'] ?: 'default.jpg') ?>" alt="">
                <div class="card-content">
                    <h3><?= escape($game['title']) ?></h3>
                    <p>Editeur: <?= escape($game['publisher']) ?></p>
                    <p>Plateforme: <?= escape($game['platform']) ?></p>
                    <p>Age Minimum: <?= escape($game['minimum_age']) ?></p>
                    <p><?= escape($game['description']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="getting-started">
    <div class="container">
        <h2>Commencer rapidement</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Configuration</h3>
                <p>Configurez votre base de données dans <code>config/database.php</code></p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Développement</h3>
                <p>Créez vos contrôleurs, modèles et vues dans leurs dossiers respectifs</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Déploiement</h3>
                <p>Uploadez votre application sur votre serveur web</p>
            </div>
        </div>
    </div>
</section> 