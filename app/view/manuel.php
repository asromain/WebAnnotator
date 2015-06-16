<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>

<h1>Manuel d'utilisation</h1><br/>

<h3>Inscription et connexion</h3>

    <p>Pour avoir un accès au système d'annotation il faut au préalabre posséder un compte utilisateur.
    Pour cela, l'utilisateur doit se rendre sur la page d'inscription et remplir les différents champs.
    Le <b>pseudo</b> doit contenir au moins <b>4 caractères</b>, le <b>mot de passe</b>, quant à lui, doit contenir au moins <b>6 caractères</b>.</p></br>

    <p>Un nouvel utilisateur appartient à une classe d'utilisateur "Lecture". Comme son nom l'indique,
     l'utilisateur peut consulter les différents textes disponibles ainsi que les annotations mais pas en ajouter. Il doit faire une demande à l'administrateur
    pour changer de classe.</p>

    <p>Une fois inscrit l'utilisateur est redirigé vers la page de connexion.
    Lors de la connexion, un accès à deux nouvelles pages est disponible : <b>Profil</b> et <b>Annoter</b>.</p></br><br/>

<h3>Administration</h3>

    <p>Deux classes d'utilisateurs sont présentes sur l'application : <b>MASTER</b> (administrateur principal) et <b>ARW</b> (pour Admin Read Write).
    L'accès à l'interface d'administration se fait en cliquant sur le liens correspond dans l'entête.</p> <br/>

    <p>Ces derniers peuvent tous les deux modifier les droits des autres utilisateurs. En revanche, seul l'administrateur MASTER peut
     effectuer les travaux de maintenance, c'est-à-dire la suppression d'utilisateurs, et à l'avenir la suppression et l'edition des textes.
     Il peut également décider d'effectuer une sauvegarde de la base de donnée.</p><br/>

    <h3 style="font-size:14px;"><i class="fa fa-info-circle"></i>Récapitulatif des droits possibles</h3>
    <div id="droit_info">
        <ul style="list-style: circle;">
            <li><b>MASTER :</b> Administrateur principal qui a tous les droits, peut désigner d'autres administrateurs. </li>
            <li><b>ARW : </b>Admin Read Write, l'utilisateur devient administrateur avec tous les droits. </li>
            <li><b>RW : </b>Read Write, lecture écriture, l'utilisateur peut lire écrire et éditer des annotations et télécharger des textes.</li>
            <li><b>R : </b>Read, lecture uniquement. Accès aux textes et annotations déjà présents.</li>
        </ul><br/>
    </div><br/>

<h3>Textes et annotation</h3>

<h3 style="font-size:14px;color:#e47b00;">- Les textes</h3>
<p>Pour sélectionner un texte, l'utilisateur doit se rendre sur la page <b>Annoter</b>. S'il a les droits adéquates (RW, ARW, ou MASTER),
il peut décider de télécharger son propre texte. </p><br/>

<p>L'utilisateur peut remplir le champ “contenu du texte” à la main, ou bien récupérer un fichier
placé sur l'ordinateur via le bouton parcourir. Il peut également effectuer un drag and drop (glisser-déposer) du fichier sur le formulaire</p><br/>

<h3 style="font-size:14px;color:#e47b00;">- Annotations</h3>
<p>Pour annoter un texte, l'utilisateur doit se rendre sur la page <b>Annoter</b> et sélectionner un texte existant.
S'il a les droits adéquates (RW, ARW, ou MASTER), il peut annoter le texte.</p></br>

<p>Pour annoter, l'utilisateur doit sélectionner un extrait de texte. Une fenêtre annotation s'affiche. Il faut ensuite remplir le champ prévu à cet effet et cliquer sur annoter.
L'annotation s'affiche alors.</p><br/>

<p>Il est à présent possible de l'<b>editer</b> en cliquant sur les points de suspensions. Ou bien de la <b>supprimer</b> en cliquant sur la croix.</p><br/>

<?php require_once 'include/footer.php'; ?>