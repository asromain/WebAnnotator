<?php


/**
 * Class AdministrationController
 */
class AdministrationController extends Controller
{
	

	/**
	 * Affiche la page d'administration globale
	 * @param null $params
	 */
	public function index($params = null)
	{
		// $this->loadModel("index");
		// $this->getModel()->query();
		// On set la variable a afficher dans la vue
		$this->set('title', 'Administration');
		// On fait de rendu de la vue indexView.php
		$this->render('administration');
		
	}

	/**
	 * Sauvegarde du graphe rdf sur le serveur
	 * @param null $params
	 */
	public function saveGraph($params = null)
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


		$this->loadModel('administration');

		$this->getModel()->saveGraph();

		echo '<p>saveGraph ok<br /><a href="'.BASE_URL.'/Administration/"> go back </a></p>';

	}

/*
	// impossible de le faire car sinon tout le onde pourrai le faire (vu que pas de test admin sans graph)
	public function loadGraph()
	{
		$this->loadModel('desc');

		$result = $this->getModel()->loadGraph();

		var_dump($result);
		echo '<p>loadGraph<br /><a href="'.BASE_URL.'/text/selection/"> go back </a></p>';
	}
*/
}