<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>

	<h3><i class="fa fa-info-circle"></i>Les différentes actions possibles</h3></br>
	<div id="droit_info">
		<ul>
			<li><i class="fa fa-database"></i><a href="<?php $this->getHomeUrl(); ?>/administration/saveGraph/"><b>Sauvegarder la base de données</b></a></li></br>
			<li><i class="fa fa-users"></i><a href="<?php $this->getHomeUrl(); ?>/user/administration/"><b>Liste des utilisateurs</b></a></li></br>
			<li><i class="fa fa-book"></i><a href="<?php $this->getHomeUrl(); ?>/text/administration/"><b>Liste des textes</b></a></li></br>
		</ul>
	</div>


<?php require_once 'include/footer.php'; ?>


