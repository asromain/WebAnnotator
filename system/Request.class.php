<?php

/**
 * Class Request
 */
class Request
{
	
    /**
     * _controller
     */
	private $_controller;
	
    /**
     * _action
     */
	private $_action;
	
    /**
     * _params
     */
	private $_params = array();

    /**
     * Constructeur
     */
	public function __construct()
	{
		// URL de base
		$baseUrl = explode('/',trim(dirname($_SERVER['SCRIPT_NAME']),'/'));
		
		// Partie ré-ecrite de l'URL
		$dynUrl = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

		// On fait la différence entre les deux
		$url = array_values(array_diff($dynUrl, $baseUrl));

		$this->_controller = isset($url[0]) ? $url[0] : "index";
		$this->_action = isset($url[1]) ? $url[1] : "index";
		$this->_params = array_slice($url, 2);
	}

    /**
     * Renvoie le controller
     * @return string
     */
	public function getController()
	{
		return $this->_controller;
	}

    /**
     * Renvoie la methode
     * @return string
     */
	public function getAction()
	{
		return $this->_action;
	}

    /**
     * Renvoie les parametres
     * @return mixed
     */
	public function getParams()
	{
		return $this->_params;
	}

}
