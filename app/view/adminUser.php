<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>


    <h3><i class="fa fa-info-circle"></i>Les différents droits possibles</h3>
    <div id="droit_info">
        <ul>
            <li><b>MASTER :</b> Administrateur principal qui a tous les droits, peut désigner d'autres administrateurs. </li>
            <li><b>ARW : </b>Admin Read Write, l'utilisateur devient administrateur avec tous les droits. </li>
            <li><b>RW : </b>Read Write, lecture écriture, l'utilisateur peut lire écrire et éditer des annotations et télécharger des textes.</li>
            <li><b>R : </b>Read, lecture uniquement. Accès aux textes et annotations déjà présents.</li>
        </ul>
    </div>

    <table>
        <tr>
            <th> Pseudo </th>
            <th> Nom </th>
            <th> Prenom </th>
            <th> Droit </th>
            <th> Attribuer droit </th>
            <?php
            if($_SESSION['user_infos']['droit'] == 'MASTER' && $_SESSION['user_infos']['pseudo'] == 'admin') { ?>
                <th> Supprimer</th>
            <?php } ?>
        </tr>
        <?php
        foreach ($usersInfos as $userInfos) {
        ?>
        <tr>
            <td><?php echo $userInfos['pseudo'] ?></td>
            <td><?php echo $userInfos['familyName'] ?></td>
            <td><?php echo $userInfos['givenName'] ?></td>
            <td><?php echo $userInfos['droit'] ?></td>
        	<td class="td_droit">
            	<?php
            	if ($userInfos['droit'] != 'MASTER' && $userPseudo != $userInfos['pseudo']) {
                    ?>
                    <form class="form_admin" action="<?php $this->getHomeUrl(); ?>/user/permission/" method="post">
                        <input type="hidden" name="pseudo" value="<?php echo $userInfos['pseudo'] ?>"/>
                        <input id="first_btn_input" class="btn_input" name="droit" type="submit" value="ARW"/>
                        <input class="btn_input" name="droit" type="submit" value="RW"/>
                        <input class="btn_input" name="droit" type="submit" value="R"/>
                    </form>
                <?php
                }
            	?>
            </td>
            <?php
            // seul l'administrateur MASTER peut voir cette colonne
            if($_SESSION['user_infos']['droit'] == 'MASTER' && $_SESSION['user_infos']['pseudo'] == 'admin') { ?>
            <td class="td_suppr">
                <?php
                if($userInfos['droit'] != 'MASTER' && $userPseudo != $userInfos['pseudo']) { ?>
                    <form class="form_admin" action="<?php $this->getHomeUrl(); ?>/user/deleteUser/" method="post">
                        <input type="hidden" name="pseudo" value="<?php echo $userInfos['pseudo'] ?>"/>
                        <input id="btn_input_suppr" class="btn_input" name="suppr" type="submit" value="X"/>
                    </form>
                <?php } ?>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>

<?php require_once 'include/footer.php'; ?>


