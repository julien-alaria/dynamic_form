<?php
// Formulaires dynamiques


    $is_edit = isset($edit_book);
    $form_action = $is_edit ? 'admin_update' : 'admin_create';
?>

<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $is_edit ? 'Modifier un livre' : 'Créer un nouveau livre' ?></legend>

        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <input type="hidden" name="media-type" value="book">

        <?php if ($is_edit): ?>
            <input type="hidden" name="book_id" value="<?= $edit_book['id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" required
                value="<?= escape($is_edit ? $edit_book['title'] : post('title', '')); ?>">
        </div>

        <div class="form-group">
            <label for="author">Auteur</label>
            <input type="text" id="author" name="author" required
                value="<?= escape($is_edit ? $edit_book['author'] : post('author', '')); ?>">
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" required
                value="<?= escape($is_edit ? $edit_book['isbn'] : post('isbn', '')); ?>">
        </div>

        <div class="form-group">
            <label for="genre_id">Genre</label>
            <select name="genre_id" id="genre_id" required>
                <option value="">Sélectionnez un genre</option>
                <?php foreach($genres as $genre): ?>
                    <option value="<?= $genre['id']; ?>"
                        <?= $is_edit && $edit_book['genre_id'] == $genre['id'] ? 'selected' : '' ?>>
                        <?= escape($genre['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="pages_nb">Nombre de pages</label>
            <input type="number" id="pages_nb" name="pages_nb" required
                value="<?= escape($is_edit ? $edit_book['pages_nb'] : post('pages_nb', '')); ?>">
        </div>

        <div class="form-group">
            <label for="summary">Résumé</label>
            <textarea id="summary" name="summary" required><?= escape($is_edit ? $edit_book['summary'] : post('summary', '')); ?></textarea>
        </div>

        <div class="form-group">
            <label for="publication_year">Année de publication</label>
            <input type="number" id="publication_year" name="publication_year" required
                value="<?= escape($is_edit ? $edit_book['publication_year'] : post('publication_year', '')); ?>">
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" required
                value="<?= escape($is_edit ? $edit_book['stock'] : post('stock', '')); ?>">
        </div>

        <div class="form-group">
            <label for="image">Image de couverture</label>
            <input type="file" id="image" name="image">
        </div>

        <input type="submit" name="<?= $form_action; ?>" value="<?= $is_edit ? 'Mettre à jour le livre' : 'Créer le livre' ?>">
    </fieldset>
</form>

// Bouton MODIFIER avec media-type à ajouter
<form action="" method="post">
    <input type="hidden" name="book_id" value="<?= $book['id']; ?>">
    <input type="hidden" name="media-type" value="book"> <!-- facultatif ici -->
    <input type="submit" name="admin_update" value="MODIFIER">
</form>

// dashboard_media_stats
function dashboard_media_stats() {
    require_admin();

    dashboard_books_stats();       // création
    dashboard_books_update();      // mise à jour
    dashboard_books_delete();      // suppression

    // Détecte si on veut modifier un livre
    if (is_post() && isset($_POST['admin_update'])) {
        $book_id = (int) post('book_id');
        $edit_book = get_book_by_id($book_id);
    }

    $books = get_all_books();
    $movies = get_all_movies();
    $video_games = get_all_video_games();
    $genres = get_all_genres();

    $data = [
        'title' => 'Gestion des médias',
        'books' => $books,
        'movies' => $movies,
        'video_games' => $video_games,
        'genres' => $genres,
        'edit_book' => $edit_book ?? null,
        'message' => 'Liste des médias enregistrés'
    ];

    load_view_with_layout('dashboard/media_stats', $data, 'admin_layout');
}
