<h2>Tableau de bord</h2>

<h2>
    Connecté en tant que: <?= htmlspecialchars($_SESSION['user_name']); ?>
</h2>

<section class="main-content">
    <h3>Statistiques d'utilisation</h3>

    <div class="stat">

        <div class="stat-user">
            <img src="<?= url('assets/images/utilisateurs.png'); ?>" height="150" alt="icon utilisateurs">

            <h4>Nombre d'utilisateurs</h4>
            <p><?= htmlspecialchars($totalUsers); ?> utilisateurs se sont connectés sur le site</p>
        </div>

        <div class="stat-media">

            <div class="media-total">
                <img src="<?= url('assets/images/medias.png'); ?>" alt="icon médias">

                <h4>Nombre de médias</h4>
                <p><?= htmlspecialchars($totalMedia); ?> médias</p>
            </div>
        </div>

        <div class="media-category">
            <div class="media-book">
                <img src="<?= url('assets/images/livre-ouvert.png'); ?>" height="150" alt="icon livres">

                <h4>Nombre de livres</h4>
                <p><?= htmlspecialchars($totalBooks); ?> livres</p>
            </div>
        </div>

        <div class="media-movie">
            <img src="<?= url('assets/images/regarder-un-film.png'); ?>" height="150"alt="icon films">

            <h4>Nombre de films</h4>
            <p><?= htmlspecialchars($totalMovies); ?> films</p>
        </div>

        <div class="media-videogames">
            <img src="<?= url('assets/images/jeux.png'); ?>" height="150" alt="icon jeux video">

            <h4>Nombre de jeux vidéo</h4>
            <p><?= htmlspecialchars($totalVideoGames); ?> jeux</p>
        </div>

    </div>

    <div class="stat-loan">

        <div class="loan-deadline">
            <img src="<?= url('assets/images/emprunter.png'); ?>" height="150" alt="icon emprunts">

             <h4>Emprunts en cours</h4>
            <p><?= htmlspecialchars($totalLoans); ?> emprunts en cours</p>
        </div>

        <div class="loan-late">
            <img src="<?= url('assets/images/en-retard.png'); ?>" height="150" alt="icon horloge retard">

            <h4>Emprunts en retard</h4>
            <p><?= htmlspecialchars($totalLoans); ?> emprunts en retard</p>
        </div>

       
    </div>

</section>