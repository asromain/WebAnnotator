<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>

<form action="<?php $this->getHomeUrl(); ?>/user/check/" method="post" >
    <h1>CONNEXION</h1>
    <?php
    // on test si l'url contient des parametres specifiques aux erreurs
    if(isset($_GET['inscription'])) {
        echo '<p class="succes"><i class="fa fa-info-circle"></i>Incription ok, veuillez vous connecter.</p>';
    }
    if(isset($_GET['connexion'])) {
        echo '<p class="erreur"><i class="fa fa-info-circle"></i>Pseudo et/ou mot de passe incorrecte.</p>';
    }
    ?>
    <fieldset id="box_field">
        <p><input id="pseudo" name="pseudo" placeholder="Pseudo" type="text" autofocus required /></p>
        <p><input class="mdp" name="mdp" placeholder="Mot de passe" type="password" required /></p>
        <p><input id="submit" type="submit" value="Connexion" /></p>
    </fieldset>
    <p id="action">
        <a href="<?php $this->getHomeUrl(); ?>/user/signup/">S'INSCRIRE</a>
    </p>
</form>

<?php require_once 'include/footer.php'; ?>
