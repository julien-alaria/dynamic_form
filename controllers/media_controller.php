<?php
// Controller unique pour tous les medias

/**
 * Liste des médias selon le type
 */
function media_index($type) {
   $books = get_all_books();
   $movies = get_all_movies();
   $games = get_all_video_games();

   $data = [
    'title' => 'Tous les médias',
    'books' => $books,
    'movies' => $movies,
    'video_games' => $games
   ];

   load_view_with_layout('media/index', $data);
}

/**
 * Détails d'un média selon son type
 */
function media_view($type, $id) {
    $media = null;

    switch ($type) {
        case 'book':
            $media = get_book_by_id($id);
            break;
        case 'movie':
            $media = get_movie_by_id($id);
            break;
        case 'video_game':
            $media = get_video_game_by_id($id);
            break;
        default:
            load_404();
            return;
    }

    $data = [
        'title' => 'Détail du média',
        'media' => $media,
        'type' => $type
    ];

    load_view_with_layout('media/view', $data);
}
