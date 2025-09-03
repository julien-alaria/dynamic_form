Structure Générale

Ce fichier est un contrôleur PHP typique d'une architecture MVC (Modèle-Vue-Contrôleur), sans framework.

Il gère :

Les routes et actions du dashboard admin

Les formulaires d’administration pour les utilisateurs et médias

Le lien entre vue, données du modèle (via fonctions), et logique métier

Focus sur le Formulaire de Gestion des Médias

IL trois types de médias : livres, films, jeux vidéo.

Où ça se passe ?

Vue : dashboard/media_stats.php (chargée via load_view_with_layout())

Contrôleur : fonction dashboard_media_stats() pour charger la vue

Formulaire : la logique est dispatchée dans la fonction route_media_actions()

Fonctionnement Étape par Étape
1. Choix du type de média

Lorsqu'un type est sélectionné via un formulaire (radio, select...), il est envoyé avec :

$_POST['media_type']


→ Stocké dans :

$_SESSION['media_type']


Cela permet de retenir le type de média entre les requêtes.

2. Dispatch via route_media_actions()

Cette fonction agit comme un router interne :

Elle vérifie le type de média via la session (book, movie, video_game)

Puis elle vérifie quelle action a été envoyée via POST :

create_*

update_*

delete_*

admin_update (édition d’un formulaire existant)

Chaque cas appelle la fonction dédiée :

create_book()         → dashboard_books_stats()
update_book()         → dashboard_books_update()
delete_book()         → dashboard_books_delete()

3. Remplissage des champs (création / modification)

Dans chaque fonction :

On récupère les données :
$title = clean_input(post('title'));
$genre_id = (int) clean_input(post('genre_id'));
// etc.

Vérifications :

Champs vides ?

Classification / âge minimum / plateforme valides ?

Vérification d’image ?

Champs de type int castés avec (int) (comme stock ou année)

Si tout est ok :

Appel de la fonction modèle create_*() ou update_*()

Flash message : succès ou erreur

4. Upload de l'image

Chaque média peut recevoir une image via :

$image = handle_image_upload() ?? 'default.jpg';


Fichier chargé depuis $_FILES

En cas d’échec, l'image "default.jpg" est utilisée

5. Suppression

Chaque type a une fonction *_delete() :

$id = post('book_id'); // ou movie_id, video_game_id
delete_book($id);      // etc.


Message flash en retour.

6. Modification (édition)

Si un formulaire d’édition est soumis :

if (isset($_POST['admin_update'])) {
    $edit_book = get_book_by_id(post('book_id'));
}


Cela permet de pré-remplir les champs du formulaire pour édition dans la vue.

Affichage du Formulaire

Le formulaire est affiché avec :

render_form($media_type, $genres, $edit_data);


Ce qui appelle la fonction correspondante :

render_book_form()

render_movie_form()

render_video_game_form()

Cela gère la vue HTML du formulaire, avec les données nécessaires (genres, champs à éditer...).

