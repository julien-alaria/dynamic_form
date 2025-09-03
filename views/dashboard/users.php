<h2>Gestion des utilisateurs</h2>

<h2>
    Connecté en tant que: <?= htmlspecialchars($_SESSION['user_name']); ?>
</h2>

<?php
    $is_edit = isset($edit_user);
    $form_action = $is_edit ? 'update_user' : 'create_user';
?>

<section>
    <form action="" method="post">
        <fieldset>
            <legend><?= $is_edit ? 'Modifier un utilisateur' : ' Entrer un utilisateur en base de donnée' ?></legend>

            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

            <?php if ($is_edit): ?>
                <input type="hidden" name="user_id" value="<?= $edit_user['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Prénom </label>
                <input type="text" id="name" name="name" required 
                       value="<?= escape($is_edit ? $edit_user['name'] : post('name', '')); ?>"
                       placeholder="Votre prénom">
            </div> 
            
            <div class="form-group">
                <label for="lastname">Nom </label>
                <input type="text" id="lastname" name="lastname" required 
                       value="<?= escape($is_edit ? $edit_user['lastname'] : post('lastname', '')); ?>"
                       placeholder="Votre nom">
            </div>
            
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required 
                       value="<?= escape($is_edit ? $edit_user['email'] : post('email', '')); ?>"
                       placeholder="votre@email.com">
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <?php $roles = ['standard', 'admin']; ?>
                <select name="role" id="role" required>
                    <option value="">Sélectionner un role</option>
                    <?php foreach($roles as $role): ?>
                        <option value="<?= $role; ?>" 
                        <?= ($is_edit && $edit_user['role'] === $role ? 'selected' : '') ?>>
                        <?= ucfirst($role); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php if (!$is_edit): ?>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       placeholder="Au moins 6 caractères">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                       placeholder="Confirmez votre mot de passe">
            </div>
            <?php endif; ?>
            
            <input type="submit" name="<?= $form_action; ?>" value="<?= $is_edit ? 'Mettre à jour' : 'Créer l\'utilisateur' ?>">

        </fieldset>
    </form>
</section>

<section class="users-dashboard">
    <h2>Utilisateurs inscrits</h2>
    <div class="cards-container">
        <?php foreach($users as $user): ?>
            <div class="card">
                <div class="card-content">
                    <p>Prénom: <?= escape($user['name']); ?></p>
                    <p>Nom: <?= escape($user['lastname']); ?></p>
                    <p>Email: <?= escape($user['email']); ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                        <input type="submit" name="admin_update" value="MODIFIER">
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                        <input type="submit" name="delete_user" value="SUPPRIMER">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>