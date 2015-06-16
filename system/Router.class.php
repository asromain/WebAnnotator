<?php

require_once 'Request.class.php';
require_once 'Controller.class.php';
require_once 'Model.class.php';

/**
 * Class Router
 */
class Router
{

    /**
     * _request
     */
	private $_request;

    /**
     * Constructeur
     */
	public function __construct()
	{
		$_request = new Request();
		
		// Récupère le nom du controller à charger
		$ctrlName = $_request->getController();

		// Modification du nom
		$ctrlName = ucfirst($ctrlName) . 'Controller';

		try
		{
			if (!class_exists($ctrlName))
				throw new Exception();

			// Instanciation du contrôleur
			$controller = new $ctrlName($this->_request);
			
			// On test si la méthode existe
			if (method_exists($controller, $_request->getAction()))
				// On appelle la méthode selon l'action de la requête
				call_user_func_array(array($controller, $_request->getAction()), array($_request->getParams()));
			else
				throw new Exception();
		}
		catch(Exception $e)
		{
			// Le contrôleur est introuvable: 404
			$controller = new Controller($this->_request);
			header('HTTP/1.0 404 Not Found');
			die($controller->e404());
		}
	}
}
