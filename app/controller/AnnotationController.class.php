<?php


/**
 * Class AnnotationController
 */
class AnnotationController extends Controller
{
	

	/**
	 * Ne fais rien
	 * @param null $params
	 */
	public function index($params = null)
	{
		
	}

	/**
	 * Ajoute une annotation dans le graph rdf et affiche les infos associé
	 * @param null $params
	 */
	public function add($params = null)
	{

		if ( $this->isUserConnected() && $this->getUserInfo('URI') != null) {
			if ( isset($_SESSION['textIdentifier']) && $_SESSION['textIdentifier'] != '') {
				if( isset($_POST['target']) && isset($_POST['content'])
				 && isset($_POST['offsetAnnot']) ) 
				{
					$target = $_POST['target'];
					$content = $_POST['content'];
					$offsetAnnot = $_POST['offsetAnnot'];

					$timestamp = time();
					
					$myFile = fopen('text/text_'.split('_', $_SESSION['textIdentifier'])[1], 'r');
					$text = '';
					while (($buffer = fgets($myFile)) !== false) {
						$text .= $buffer;
					}
					fclose($myFile);

					$text = preg_replace('#[\n\r]#','',$text); // TODO MAIS LOL

					$before = substr($text, $offsetAnnot - 10, 10);
					// $before = substr($text, ($offsetAnnot > 10 ) ? $offsetAnnot - 10 : 0 , 10);
					$in = substr($text, $offsetAnnot, strlen($target));
					$after = substr($text, $offsetAnnot+strlen($target), 10);

					$hash = OAHash::getHash($before, $in, $after);



					$this->loadModel('annotation');

					$result = $this->getModel()->insertAnnotation($timestamp, $this->getUserInfo('URI'), $hash, $content, $_SESSION['textIdentifier']);

					// faire ce test sur le code retour
					// if ($resultCode == 200) {
					
					$resultAnnot = $this->getModel()->getAnnotationFromId($timestamp, $_SESSION['textIdentifier']);

					$arrobj = new ArrayObject($resultAnnot);
					$i = $arrobj->getIterator();
					if ($i->valid()) {

						echo json_encode( array(
										'annotURI' => $this->getModel()->getBaseDescURI().'annot_'.$timestamp,
										'annotatedAt' => $i->current()->annotatedAt->getValue(),
										'annotatedBy' => split('user_', $i->current()->annotatedBy->getValue())[1],
										'annotatedByUri' => $i->current()->annotatedBy->getValue(),
										'target' => OAHash::getTarget($i->current()->target->getValue()),
										'body' => $i->current()->body->getValue(),
										'source' => $_SESSION['textIdentifier'],
										'offset' => OAHash::getOffset($text, $i->current()->target->getValue())
									));
					}
					else {
						echo "cette erreur ne devrais pas arriver";
						echo $result;
					}
				} else {
					echo 'Veuillez remplir les champs';
				}
			}
			else {
				echo "pas de texe associé";
			}
		}
		else {
			echo "pas connecte";
		}
	}

	/**
	 * Supprime une annotation dans le graph rdf et affiche 1 si réussi
	 * @param null $params
	 */
	public function delete($params = null)
	{

		if ( $this->isUserConnected() && $this->getUserInfo('URI') != null) {
			if ( isset($_SESSION['textIdentifier']) && $_SESSION['textIdentifier'] != '') {
				if( isset($_POST['timestamp']) ) 
				{
					$timestamp = $_POST['timestamp'];


					$this->loadModel('annotation');

					$result = $this->getModel()->deleteAnnotation($timestamp, $_SESSION['textIdentifier']);

					echo "1";
				} else {
					echo 'Veuillez remplir les champs';
				}
			}
			else {
				echo "pas de texe associé";
			}
		}
		else {
			echo "pas connecte";
		}
	}

	/**
	 * Modifie de copr d'une annotation dans le graph rdf et affiche 1 si reussi
	 * @param null $params
	 */
	public function update($params = null)
	{

		if ( $this->isUserConnected() && $this->getUserInfo('URI') != null) {
			if ( isset($_SESSION['textIdentifier']) && $_SESSION['textIdentifier'] != '') {
				if( isset($_POST['timestamp']) && isset($_POST['newBody']) ) 
				{
					$timestamp = $_POST['timestamp'];
					$newBody = $_POST['newBody'];

					$this->loadModel('annotation');

					$success = $this->getModel()->updateAnnotation($timestamp, $_SESSION['textIdentifier'], $newBody);

					if ($success) {
						echo "1";
					}
					else {
						echo "0";
					}
				} else {
					echo 'Veuillez remplir les champs';
				}
			}
			else {
				echo "pas de texe associé";
			}
		}
		else {
			echo "pas connecte";
		}
	}

	/**
	 * Affiche les infos associé a toutes les annotaions d'un texte
	 * @param null $params
	 */
	public function get($params = null)
	{

		if ( $this->isUserConnected() && $this->getUserInfo('pseudo') != null) {
			if ( isset($_SESSION['textIdentifier']) && $_SESSION['textIdentifier'] != '') {
				
				$myFile = fopen('text/text_'.split('_', $_SESSION['textIdentifier'])[1], 'r');

				$text = '';
				while (($buffer = fgets($myFile)) !== false) {
					$text .= $buffer;
				}
				fclose($myFile);

				$text = preg_replace('#[\n\r]#','',$text);

				$this->loadModel('annotation');

				$result = $this->getModel()->getAllAnnotationFromTextId($_SESSION['textIdentifier']);

				$arrobj = new ArrayObject($result);

				for($i = $arrobj->getIterator(); $i->valid(); $i->next()) {
					$annotations[] = array(
										'annotURI' => $i->current()->annotURI->getUri(),
										'annotatedAt' => $i->current()->annotatedAt->getValue(),
										'annotatedBy' => split('user_', $i->current()->annotatedBy->getValue())[1],
										'annotatedByUri' => $i->current()->annotatedBy->getValue(),
										'target' => OAHash::getTarget($i->current()->target->getValue()),
										'body' => $i->current()->body->getValue(),
										'offset' => OAHash::getOffset($text, $i->current()->target->getValue())
										);
				}
				if (isset($annotations)) {
					echo json_encode($annotations);
				}
				else {
					echo "null";
				}
			}
			else {
				echo "pas de texe associé";
			}
		}
		else {
			echo "pas connecte";
		}
	}

}