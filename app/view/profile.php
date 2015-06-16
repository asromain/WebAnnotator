<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>

<?php
    // si le membre est connecte on affiche les infos
    $isLoggedIn = $this->isUserConnected();
    if($isLoggedIn) {
?>

    <form action="<?php $this->getHomeUrl(); ?>/user/editProfile/" method="post">
        <h1>Editer son profil</h1>
        <?php
        // on test si l'url contient des parametres specifiques aux erreurs
        if (isset($_GET['info']) && isset($_GET['info']) == 1) {
            echo '<p class="succes"><i class="fa fa-info-circle"></i>Information correctement mise à jour</p>';
        } else if (isset($_GET['newinfo']) && isset($_GET['newinfo']) == 1) {
            echo '<p class="succes"><i class="fa fa-info-circle"></i>Renseignement de champs effectué.</p>';
        } else if (isset($_GET['erreur']) && isset($_GET['erreur']) == 1) {
            echo '<p class="erreur"><i class="fa fa-info-circle"></i>Renseignez au moins un champ.</p>';
        } else if (isset($_GET['mdp']) && isset($_GET['mdp']) == 1) {
            echo '<p class="erreur"><i class="fa fa-info-circle"></i>Le mot de passe doit comporter au moins 6 caractères.</p>';
        }
        ?>
        <div id="user_info">
            <?php $this->loadModel('user'); ?>
            <p><i class="fa fa-user"></i><b>Pseudo :</b> <em><?php echo ucfirst($this->getUserInfo('pseudo')); ?></em></p> <br/>
            <p><i class="fa fa-user"></i><b>Prenom :</b> <em><?php $this->getModel()->displayInfo($this->getUserInfo('pseudo'), 'givenName'); ?></em></p><br/>
            <p><i class="fa fa-user"></i><b>Nom :</b> <em><?php $this->getModel()->displayInfo($this->getUserInfo('pseudo'), 'familyName'); ?></em></p><br/>
            <?php
            $droit = $this->getModel()->getUserInfo($_SESSION['user_infos']['pseudo'], 'permission');
            switch ($droit) {
                case 'MASTER':
                    $droit .= " (administrateur principal)";
                    break;
                case 'ARW':
                    $droit .= " (administrateur)";
                    break;
                case 'RW':
                    $droit .= " (lecture et écriture)";
                    break;
                case 'R':
                    $droit .= " (lecture uniquement)";
                    break;
                default:
                    $droit = " NULL (aucun droit renseigné)";
            }
            ?>
            <p><i class="fa fa-user"></i><b>Droit :</b> <em><?php echo $droit; ?></em></p>
        </div>
        <p><input id="pseudo" name="prenom" placeholder="Prénom" type="text"/></p>
        <p><input id="pseudo" name="nom" placeholder="Nom" type="text"/></em></p>
        <p><input class="mdp" name="mdp" placeholder="Modifier le mot de passe" type="password"/></em></p>
        <p><input id="submit" type="submit" value="Enregistrer"></p>
    </form>

<?php
    }
    // sinon on redirige vers index
    else {
        header('Location: '.BASE_URL);
    }
?>

<?php require_once 'include/footer.php'; ?>
