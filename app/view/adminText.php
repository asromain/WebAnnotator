<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>


	<h3><i class="fa fa-info-circle"></i>Les Textes</h3>
	<table>
		<tr>
			<th> Titre </th>
			<th> Créateur </th>
			<th> Sujet </th>
			<th> Publieur </th>
			<th> Date </th>
			<th> Identificateur </th>
			<th> Source </th>
			<th> Langage </th>
			<th> Action </th>
		</tr>
		<?php
		if (isset($textsInfos)) {
			foreach ($textsInfos as $textInfos) {
		?>
		<tr>
			<td><?php echo $textInfos['title'] ?></td>
			<td><?php echo $textInfos['creator'] ?></td>
			<td><?php echo $textInfos['subject'] ?></td>
			<td><a href="<?php echo $textInfos['publisher'] ?>"><?php echo $textInfos['publisher'] ?></a></td>
			<td><?php echo $textInfos['date'] ?></td>
			<td><a href="<?php $this->getHomeUrl(); ?>/text/annotate/<?php echo $textInfos['identifier'] ?>"><?php echo $textInfos['identifier'] ?></a></td>
			<td><?php echo $textInfos['source'] ?></td>
			<td><?php echo $textInfos['language'] ?></td>
			<td>supp/modif</td>
		</tr>
		<?php
			}
		} else {
		?>
		<tr>
			<td colspan="9">Aucun texte disponible.</td>
		</tr>
		<?php
		}
		?>
	</table>

<?php require_once 'include/footer.php'; ?>


