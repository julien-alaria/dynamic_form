<h2>Gestion des médias</h2>

<h2>
    Connecté en tant que: <?= htmlspecialchars($_SESSION['user_name']); ?>
</h2>

<?php 
if (isset($_POST['media_type'])) {
    $_SESSION['media_type'] = $_POST['media_type'];
}

$media_type = $_SESSION['media_type'] ?? null;
?>

<form action="" method="post">
    <fieldset>
        <legend>Sélection du média</legend>

        <div>
            <input type="radio" id="book" name="media_type" value="book" <?= $media_type === 'book' ? 'checked' : '' ?>>
            <label for="book">Livre</label>
        
            <input type="radio" id="movie" name="media_type" value="movie" <?= $media_type === 'movie' ? 'checked' : '' ?>>
            <label for="movie">Film</label>
        
            <input type="radio" id="video_game" name="media_type" value="video_game" <?= $media_type === 'video_game' ? 'checked' : '' ?>>
            <label for="video_game">Jeu vidéo</label>
        </div>

        <input type="submit" value="valider">
    </fieldset>
</form>

<?php

if ($media_type) {
    $edit_data = null;
    if ($media_type === 'book' && isset($edit_book)) $edit_data = $edit_book;
    if ($media_type === 'movie' && isset($edit_movie)) $edit_data = $edit_movie;
    if ($media_type === 'video_game' && isset($edit_video_game)) $edit_data = $edit_video_game;

    render_form($media_type, $genres, $edit_data);
}

function render_book_form($genres, $edit_data = null) {
    $is_edit_book = !empty($edit_data);
    $form_action = $is_edit_book ? 'update_book' : 'create_book';
?>
<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $is_edit_book ? 'Modifier un livre' : 'Entrer un livre en base de donnée' ?></legend>

        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

        <?php if ($is_edit_book): ?>
                <input type="hidden" name="book_id" value="<?= $edit_data['id']; ?>">
        <?php endif; ?>

        <label for="title_book">Titre:</label>
        <input type="text" id="title_book" name="title" required value="<?= escape($is_edit_book ? $edit_data['title'] : post('title', '')); ?>">

        <label for="author_book">Auteur:</label>
        <input type="text" id="author_book" name="author" required value="<?= escape($is_edit_book ? $edit_data['author'] : post('author', '')); ?>">

        <label for="isbn_book">Isbn:</label>
        <input type="text" id="isbn_book" name="isbn" required value="<?= escape($is_edit_book ? $edit_data['isbn'] : post('isbn', '')); ?>">

        <label for="genre_id_book">Genre:</label>
        <select name="genre_id" id="genre_id_book" required>
            <option value="">Sélectionner un genre</option>
            <?php foreach($genres as $genre): ?>
                <option value="<?= $genre['id']; ?>"><?= htmlspecialchars($genre['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="pages_nb_book">Nombre de pages:</label>
        <input type="number" id="pages_nb_book" name="pages_nb" required value="<?= escape($is_edit_book ? $edit_data['pages_nb'] : post('pages_nb')); ?>">

        <label for="summary_book">Résumé:</label>
        <textarea name="summary" id="summary_book" rows="6" required><?= escape($is_edit_book ? $edit_data['summary'] : post('summary', '')); ?></textarea>


        <label for="publication_year_book">Année de publication:</label>
        <input type="number" id="publication_year_book" name="publication_year" required value="<?= escape($is_edit_book ? $edit_data['publication_year'] : post('publication_year')); ?>">

        <label for="image_book">Image:</label>
        <input type="file" id="image_book" name="image">

        <label for="stock_book">Stock:</label>
        <input type="number" id="stock_book" name="stock" required value="<?= escape($is_edit_book ? $edit_data['stock'] : post('stock')); ?>">
        
        <input type="hidden" name="media-type" value="book">
        <input type="submit" name="<?= $form_action ?>" value="valider">
    </fieldset>
</form>
<?php
}

function render_movie_form($genres, $edit_data = null) {
    $is_edit_movie = !empty($edit_data);
    $form_action = $is_edit_movie ? 'update_movie' : 'create_movie';
?>
<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $is_edit_movie ? 'Modifier un film' : 'Entrer un film en base de donnée' ?></legend>

        <?php if ($is_edit_movie): ?>
                <input type="hidden" name="movie_id" value="<?= $edit_data['id']; ?>">
        <?php endif; ?>

        <label for="title_movie">Titre:</label>
        <input type="text" id="title_movie" name="title" required value="<?= escape($is_edit_movie ? $edit_data['title'] : post('title', '')); ?>">

        <label for="director_movie">Réalisateur:</label>
        <input type="text" id="director_movie" name="director" required value="<?= escape($is_edit_movie ? $edit_data['director'] : post('director', '')); ?>">

        <label for="year_movie">Année de sortie:</label>
        <input type="number" id="year_movie" name="year" required value="<?= escape($is_edit_movie ? $edit_data['year'] : post('year', '')); ?>">

        <label for="genre_id_movie">Genre:</label>
        <select name="genre_id" id="genre_id_movie" required>
            <option value="">Sélectionner un genre</option>
            <?php foreach($genres as $genre): ?>
                <option value="<?= $genre['id']; ?>"><?= htmlspecialchars($genre['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="duration_movie">Durée (en min):</label>
        <input type="number" id="duration_movie" name="duration" required value="<?= escape($is_edit_movie ? $edit_data['duration'] : post('duration', '')); ?>">

        <label for="synopsis_movie">Synopsis:</label>
        <textarea name="synopsis" id="synopsis_movie" rows="6" required>
            <?= escape($is_edit_movie ? $edit_data['synopsis'] : post('synopsis', '')); ?>
        </textarea>

        <label for="classification_movie">classification:</label>
        <select name="classification" id="classification_movie" required>
            <option value="">Classification</option>
            <option value="tous_publics">tous publics</option>
            <option value="12">12</option>
            <option value="16">16</option>
            <option value="18">18</option>
        </select>

        <label for="image_movie">Image:</label>
        <input type="file" id="image_movie" name="image">

        <label for="stock_movie">Stock:</label>
        <input type="number" id="stock_movie" name="stock" required value="<?= escape($is_edit_movie ? $edit_data['stock'] : post('stock', '')); ?>">
        
        <input type="hidden" name="media-type" value="movie">
        <input type="submit" name="<?= $form_action ?>" value="valider">
    </fieldset>
</form>
<?php
}

function render_video_game_form($genres, $edit_data = null) {
    $is_edit_video_game = !empty($edit_data);
    $form_action = $is_edit_video_game ? 'update_video_game' : 'create_video_game';
?>
<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $is_edit_video_game ? 'Modifier un jeu vidéo' : 'Entrer un jeu vidéo en base de donnée' ?></legend>
        
        <?php if ($is_edit_video_game): ?>
                <input type="hidden" name="video_game_id" value="<?= $edit_data['id']; ?>">
        <?php endif; ?>

        <label for="title_video_game">Titre:</label>
        <input type="text" id="title_video_game" name="title" required value="<?= escape($is_edit_video_game ? $edit_data['title'] : post('title', '')); ?>">

        <label for="publisher_video_game">Editeur:</label>
        <input type="text" id="publisher_video_game" name="publisher" required value="<?= escape($is_edit_video_game ? $edit_data['publisher'] : post('publisher', '')); ?>">

        <label for="platform_video_game">Plateforme:</label>
        <select name="platform" id="platform_video_game" required>
            <option value="">Sélectionnez une plateforme</option>
            <option value="PC" <?= $is_edit_video_game && $edit_data['platform'] === 'PC' ? 'selected' : '' ?>>PC</option>
            <option value="Playstation" <?= $is_edit_video_game && $edit_data['platform'] === 'Playstation' ? 'selected' : '' ?>>Playstation</option>
            <option value="Xbox" <?= $is_edit_video_game && $edit_data['platform'] === 'Xbox' ? 'selected' : '' ?>>Xbox</option>
            <option value="Nintendo" <?= $is_edit_video_game && $edit_data['platform'] === 'Nintendo' ? 'selected' : '' ?>>Nintendo</option>
            <option value="Mobile" <?= $is_edit_video_game && $edit_data['platform'] === 'Mobile' ? 'selected' : '' ?>>Mobile</option>
        </select>

        <label for="genre_id_video_game">Genre:</label>
        <select name="genre_id" id="genre_id_video_game" required>
            <option value="">Sélectionner un genre</option>
            <?php foreach($genres as $genre): ?>
                <option value="<?= $genre['id']; ?>"><?= htmlspecialchars($genre['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="minimum_age_video_game">Age recommandé:</label>
        <select name="minimum_age" id="minimum_age_video_game" required>
            <option value="">Sélectionnez un  âge</option>
            <option value="3" <?= $is_edit_video_game && $edit_data['minimum_age'] == 3 ? 'selected' : '' ?>>3</option>
            <option value="7" <?= $is_edit_video_game && $edit_data['minimum_age'] == 7 ? 'selected' : '' ?>>7</option>
            <option value="12" <?= $is_edit_video_game && $edit_data['minimum_age'] == 12 ? 'selected' : '' ?>>12</option>
            <option value="16" <?= $is_edit_video_game && $edit_data['minimum_age'] == 16 ? 'selected' : '' ?>>16</option>
            <option value="18" <?= $is_edit_video_game && $edit_data['minimum_age'] == 18 ? 'selected' : '' ?>>18</option>
        </select>

        <label for="description_video_game">Description:</label>
        <textarea name="description" id="description_video_game" rows="6" required>
            <?= escape($is_edit_video_game ? $edit_data['description'] : post('description', '')); ?>
        </textarea>

        <label for="image_video_game">Image:</label>
        <input type="file" id="image_video_game" name="image">

        <label for="stock_video_game">Stock:</label>
        <input type="number" id="stock_video_game" name="stock" required value="<?= escape($is_edit_video_game ? $edit_data['stock'] : post('stock', '')); ?>">
        
        <input type="hidden" name="media-type" value="video_game">
        <input type="submit" name="<?= $form_action ?>" value="valider">
    </fieldset>
</form>
<?php
}
?>

<section class="media-dashboard">

    <h2>Médias dans la Médiatheque</h2>

    <div class="cards-container">
        <?php foreach($books as $book): ?>
            <div class="card">
                <div class="card-content">
                    <p>Titre: <?= escape($book['title']); ?></p>
                    <p>Auteur: <?= escape($book['author']); ?></p>
                    <p>Isbn: <?= escape($book['isbn']); ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="media_type" value="book">
                        <input type="hidden" name="book_id" value="<?= $book['id']; ?>">
                        <input type="submit" name="admin_update" value="MODIFIER">
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="media_type" value="book">
                        <input type="hidden" name="book_id" value="<?= $book['id']; ?>">
                        <input type="submit" name="delete_book" value="SUPPRIMER">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="cards-container">
        <?php foreach($movies as $movie): ?>
            <div class="card">
                <div class="card-content">
                    <p>Titre: <?= escape($movie['title']); ?></p>
                    <p>Réalisateur: <?= escape($movie['director']); ?></p>
                    <p>Année: <?= escape($movie['year']); ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="media_type" value="movie">
                        <input type="hidden" name="movie_id" value="<?= $movie['id']; ?>">
                        <input type="submit" name="admin_update" value="MODIFIER">
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="media_type" value="movie">
                        <input type="hidden" name="movie_id" value="<?= $movie['id']; ?>">
                        <input type="submit" name="delete_movie" value="SUPPRIMER">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="cards-container">
        <?php foreach($video_games as $video_game): ?>
            <div class="card">
                <div class="card-content">
                    <p>Titre: <?= escape($video_game['title']); ?></p>
                    <p>Plateforme: <?= escape($video_game['platform']); ?></p>
                    <p>Année: <?= escape($video_game['minimum_age']); ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="media_type" value="video_game">
                        <input type="hidden" name="video_game_id" value="<?= $video_game['id']; ?>">
                        <input type="submit" name="admin_update" value="MODIFIER">
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="media_type" value="video_game">
                        <input type="hidden" name="video_game_id" value="<?= $video_game['id']; ?>">
                        <input type="submit" name="delete_video_game" value="SUPPRIMER">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>