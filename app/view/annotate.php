<?php require_once 'include/meta.php'; ?>

	<link rel="stylesheet" type="text/css" href="<?php $this->getPath('css/annoter.css'); ?>">

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="<?php $this->getPath('js/floatForm.js'); ?>"></script>
	<script type="text/javascript" src="<?php $this->getPath('js/annoter.js'); ?>"></script>

<?php require_once 'include/header.php'; ?>

    <div id="explication_annotation">
        <h1>Comment annoter</h1>
        <p>Pour annoter, veuillez sélectionner un extrait de texte. Une fenêtre annotation s'affiche. Veuillez ensuite remplir le champ prévu à cet effet et cliquez sur annoter.
            Votre annotation s'affiche alors. Il est à présent possible de l'editer en cliquant sur les points de suspensions. Ou bien de la supprimer en cliquant sur la croix.</p>
    </div>

	<h1 style="color:black"><?php echo $textTitle; ?></h1>
	<div id="workspace">
		<div id="annotespace">
			<div id="backHighlight"></div>
			<div id="textContent"><?php echo htmlentities($text); ?></div><!-- important ne pas mettre de l'indentation dans cette balise ! -->
		</div>
		<?php if($_SESSION['user_infos']['droit'] != 'R') {?>
		<div id="floatForm">
			<form id="form_annot_select" onsubmit="sendAnnot(); return false;">
				<div class="arrow-left"></div>
				<p><label for="content">Votre annotation:</label></p>
				<p><input type="text" class="input-text" placeholder="votre annotation"  id="content" name="content" autofocus required /></p>
				<input type="hidden" id="target" name="target" readonly />
				<input type="hidden" id="offset" name="offset" readonly />
				<p><button class="droit" type="submit">Annoter</button></p>
			</form>
		</div>
		<?php } ?>
		<div id="annotInfosModel" class="annotInfos">
		<?php if($_SESSION['user_infos']['droit'] != 'R') {?>
			<div class="delAnnot"> x </div>
			<div class="editAnnot"> ... </div>
			<div class="cancelAnnot">annuler</div>
		<?php } ?>
			<p class="authorName">Auteur</p>
			<p class="annotBody">Annotation Body</p>
		<?php if($_SESSION['user_infos']['droit'] != 'R') {?>
			<p class="annotBodyForm">
				<input type="text" class="contentEdit" value="Annotation Body input" />
				<button class="droit sendBtn">Edit</button>
			</p>
		<?php } ?>
		</div>
		<div class="clear_float"></div>

	</div>

	<div id="urlForJs" style="display: none;"><?php echo $urlForJs; ?></div>

<?php require_once 'include/footer.php'; ?>
