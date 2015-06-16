<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>

<form action="<?php $this->getHomeUrl(); ?>/user/insert/" method="post" >
    <h1>INSCRIPTION</h1>
    <?php

    // on test si l'url contient des parametres specifiques aux erreurs
    if(isset($_GET['inscription'])) {
        switch ($_GET['inscription']) {
            case 'pseudo_exist':
                echo '<p class="erreur"><i class="fa fa-info-circle"></i>Le pseudo est déjà utilisé.</p>';
                break;
            case 'short_pseudo':
                echo '<p class="erreur"><i class="fa fa-info-circle"></i>Le pseudo doit contenir au moins 4 caractères.</p>';
                break;
            case 'invalid_pseudo':
                echo '<p class="erreur"><i class="fa fa-info-circle"></i>Le pseudo doit contenir que des chiffres et lettres.</p>';
                break;
            case 'mdp':
                echo '<p class="erreur"><i class="fa fa-info-circle"></i>Les mots de passes ne correspondent pas.</p>';
                break;
            case 'short_mdp':
                echo '<p class="erreur"><i class="fa fa-info-circle"></i>Le mot de passe doit avoir au moins 6 caractères.</p>';
                break;
            default:
                echo '<p class="erreur"><i class="fa fa-info-circle"></i>Erreur lors de l\'inscription.</p>';
        }
    }

    ?>
    <fieldset id="box_field">
        <p><input id="pseudo" name="pseudo" placeholder="Pseudo" type="text" autofocus required /></p>
        <p><input name="droit" value="R" type="hidden" /></p>
        <p><input class="mdp" name="mdp" placeholder="Mot de passe" type="password" required /></p>
        <p><input class="mdp" name="mdp_conf" placeholder="Mot de passe confirmation" type="password" required /></p>
        <p><input id="submit" type="submit" value="S'enregistrer"></p>
    </fieldset>
    <p id="action">
        <a href="<?php $this->getHomeUrl(); ?>/user/login/">SE CONNECTER</a>
    </p>
</form>

<?php require_once 'include/footer.php'; ?>