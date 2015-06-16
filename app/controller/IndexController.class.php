<?php


/**
 * Class IndexController
 */
class IndexController extends Controller
{
	

	/**
	 * Affiche la page d'accueil du web site
	 * @param null $params
	 */
	public function index($params = null)
	{
		// $this->loadModel("index");
		// $this->getModel()->query();
		// On set la variable a afficher dans la vue
		$this->set('title', 'Accueil');
		// On fait de rendu de la vue indexView.php
		$this->render('indexView');
		
	}

}