<?php


/**
 * Class TextController
 */
class TextController extends Controller
{
	

	/**
	 * Redirige vers la page d'accueil
	 * @param null $params
	 */
	public function index($params = null)
	{
		// $this->loadModel("index");
		// $this->getModel()->query();
		// On set la variable a afficher dans la vue
		//$this->set('title', 'Accueil');
		// On fait de rendu de la vue indexView.php
		//$this->render('indexView');
		header('Location: '.BASE_URL.'');
	}

	/**
	 * Ajoute un texte dans le graphe rdf
	 * @param null $params
	 */
	public function add($params = null)
	{
		if ( $this->isUserConnected() && $this->getUserInfo('URI') != null) {

			if( isset($_POST['title']) && isset($_POST['content']) && 
				isset($_POST['keyWord']) && isset($_POST['source']) && 
				isset($_POST['language']) && isset($_POST['author']) )
			{
				$this->loadModel('text');

				$title = $_POST['title'];
				$author = $_POST['author'];
				// $keyWord = explode(',', $_POST['keyWord']);
                $keyWord = $_POST['keyWord'];

                $timestamp = time() . '';
				$source = $_POST['source'];
				$language = $_POST['language'];
				$textId = 'text_'.$timestamp;

				// textarea pour ajout manuel de fichier
				$content = $_POST['content'];

				// TODO permission d'ecriture
				$myFile = fopen(TEXT_ROOT.DS.'text_'.$timestamp, 'a+');

				fseek($myFile, 0);
				fputs($myFile, $content);

				fclose($myFile);

				$result = $this->getModel()->insertText($title, $author, $keyWord, $this->getUserInfo('URI'), $timestamp, $source, $language);

				$code = split(' ' , $result)[1];

				if ($code == "200") {
					// success
					header('Location: '.BASE_URL.'/text/selection/');
				}

				echo "erreur add text :";
				echo $result;
				echo '<p><a href="'.BASE_URL.'/text/selection/"> text selection </a></p>';

			} else {
				echo 'Veuillez remplir les champs';
			}
		}
		else {
			echo "pas connecte";
		}
	}


	/**
	 * Affiche la page d'ajout et selection d'un texte
	 * @param null $params
	 */
	public function selection($params = null)
	{
		if (! $this->isUserConnected() ) {
			// echo "user not connected";
			header('Location: '.BASE_URL.'/user/login/');
			return ;
		}


		$this->loadModel('text');


		unset($_SESSION['textIdentifier']);

		$result = $this->getModel()->getAllText();

		$arrobj = new ArrayObject($result);

		$dispTexts = '<ul>';
		for($i = $arrobj->getIterator(); $i->valid(); $i->next()) {
			// TODO rendre le lien bon
			$dispTexts .= '<li><i class="fa fa-book"></i><a href="'.BASE_URL.'/text/annotate/'.$i->current()->identifier->getValue().'/">' . $i->current()->title->getValue() . '</a></li>';
		}
		$dispTexts .= '</ul>';

		$this->set('dispTexts', $dispTexts);

		$this->set('title', 'Selectionner un texte');

		$this->render('textSelection');
	}

	/**
	 * Affiche la page qui permet d'annoter un texte
	 * @param null $params
	 */
	public function annotate($params = null)
	{

		// S'il existe la variable session, on affiche le pseudo
		if ( $this->isUserConnected() && $this->getUserInfo('pseudo') != null) {
			$pseudo = $this->getUserInfo('pseudo');
		} else {
			// echo 'Location: user login ; no session';
			header('Location: '.BASE_URL.'/user/login/');
		}

		if (isset($params[0]) && $params[0] != '') {
			$textIdentifier = $params[0];
			$_SESSION['textIdentifier'] = $textIdentifier;
		}
		else  {
			// echo 'Location: textSelection ; no param';
			header('Location: '.BASE_URL.'/text/selection/');
		}
		
		$this->loadModel('text');

		$result = $this->getModel()->getTextById($textIdentifier);

		$arrobj = new ArrayObject($result);

		$i = $arrobj->getIterator();

		if ($i->valid()) {
			// if($i->current()->publisher->getValue() == 'http://ter.com/'.$pseudo) {

				$textTitle = $i->current()->title->getValue();
				$myFile = fopen('text/text_'.$i->current()->date->getValue(), 'r');

				$text = '';
				while (($buffer = fgets($myFile)) !== false) {
					$text .= $buffer;
				}

				fclose($myFile);


				// $text = parseText($sparql, $text, $i->current()->identifier->getValue());
				$this->set('text', $text);
				$urlForJs = BASE_URL;
				$this->set('urlForJs', $urlForJs);
				$this->set('textTitle', $textTitle);
				$this->set('title', 'Annoter un texte');

				$this->render('annotate');

			// }
			// else {
				// echo "texte appartient pas a la personne connecte";
			// }
		}
		else {
			echo "pas de texte de cet id";
		}

	}


	/**
	 * Methode pour visualiser la page d'administration text
	 * @param null $params
	 */
	public function administration($params = null)
	{
		// user connecter
		if (!$this->isUserConnected()) {
			header('Location: '.BASE_URL);
			return ;
		}
		// user admin
		if ($this->getUserInfo('droit') != 'ARW' && $this->getUserInfo('droit') != 'MASTER') {
			header('Location: '.BASE_URL);
			return ;
		}

		$this->loadModel('text');

		$result = $this->getModel()->getAllText();

		$arrobj = new ArrayObject($result);

		for($i = $arrobj->getIterator(); $i->valid(); $i->next())
		{
			$textsInfos[] = array(
				'title' => $i->current()->title->getValue(),
				'creator' => $i->current()->creator->getValue(),
				'subject' => $i->current()->subject->getValue(),
				'publisher' => $i->current()->publisher->getValue(),
				'date' => $i->current()->date->getValue(),
				'type' => $i->current()->type->getValue(),
				'format' => $i->current()->format->getValue(),
				'identifier' => $i->current()->identifier->getValue(),
				'source' => $i->current()->source,
				'language' => $i->current()->language->getValue()
				);
		}

		if (isset($textsInfos)) {
			$this->set('textsInfos', $textsInfos);
		}

		// On set la variable a afficher sur dans la vue
		$this->set('title', 'Administration des textes');
		// On fait le rendu de la vue signup.php
		$this->render('adminText');
	}


}