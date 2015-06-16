<?php require_once 'include/meta.php'; ?>
	<link rel="stylesheet" type="text/css" href="<?php $this->getPath('css/select.css'); ?>">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="<?php $this->getPath('js/dragNdropFile.js'); ?>"></script>
<?php require_once 'include/header.php'; ?>

        <?php if($_SESSION['user_infos']['droit'] != "R") { ?>
        <form action="<?php $this->getHomeUrl(); ?>/text/add/" method="post" id="text_form" enctype="multipart/form-data">
            <h1>AJOUTER UN TEXTE</h1>
            <p id="info_annotation"><i class="fa fa-info-circle"></i>Formulaire permettant d'ajouter un texte sur le serveur. Trois façons de l'ajouter :</p>
            <div id="info_upload">
                <ul>
                    <li>Effectuer un glisser/déposer d'un fichier dans la zone de texte (la zone se surligne en rouge).</li>
                    <li>Effectuer un copier/coller du texte dans la zone prévue à cette effet.</li>
                    <li>Rechercher son fichier sur l'ordinateur en cliquant sur le bouton parcourir.</li>
                </ul><br/>
            </div>
            <p><input type="text" name="title" placeholder="Son titre" autofocus required></p>
            <p><input type="text" name="author" placeholder="Son auteur" required ></p>
            <p><input type="text" name="keyWord" placeholder="Mots clés séparés par des virgules" ></p>
            <p><input type="text" name="source" placeholder="Sa source" ></p>
            <p><input type="text" name="language" placeholder="Sa langue" ></p>
            <p><textarea id="dropTextZone" class="dropTextZone" name="content" placeholder="Texte à ajouter sur le serveur"></textarea></p>
            <p>
                <input class="dropTextZone" type="file" id="fileSelect" >
            </p>
            <p><button style="width:150px;" type="submit" id="submit">Ajouter le texte</button></p>
        </form>
        <?php } ?>

        <div id="text_list">
            <h1>TEXTES DISPONIBLES</h1>
            <p> Ci-dessous, les textes diponibles à annoter. Cliquer sur le lien correspondant pour accéder à l'interface d'annotation.</p>
            <?php echo $dispTexts; ?>
        </div>

        <div class="clear_float"></div>
    </div>


<?php require_once 'include/footer.php'; ?>