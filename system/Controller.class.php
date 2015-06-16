<?php

/**
 * Class Controller
 */
class Controller
{
    /**
     * Instance du modèle
     */
	protected $_model;
	
    /**
     * Requête de l'utilisateur
     */
	protected $_request;

    /**
     * Variables à envoyer à la vue
     */
	protected $_vars = array();

    /**
     * Constructeur
     */
	public function __construct($request)
	{
		$this->_request = $request;
	}

    /**
     * Charge le modèle de données
     * @param $model
     */
	protected function loadModel($model)
	{
		$model = ucfirst($model).'Model';
		try
		{
			$this->_model = new $model();
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

    /**
     * Retourne le modèle
     * @return object
     */
	protected function getModel()
	{
		return $this->_model;
	}

    /**
     * Ajoute une variable pour la vue
     * @param $key
     * @param $value
     */
	protected function set($key, $value)
	{
		$this->_vars[$key] = $value;
	}

    /**
     * Fait le rendu de la vue
     * @param $view
     */
	public function render($view)
	{
		//  On extrait les variables à afficher dans la vue
		extract($this->_vars);
		//  On affiche le vue
		require(VIEW_ROOT.DS.$view.'.php');
	}

    /**
     * Affiche l'erreur 404
     */
	public function e404()
	{
		$this->set('title', 'Page introuvable');
		extract($this->_vars);
		require(VIEW_ROOT.DS.'404.php');
	}

    /**
     * Récupère le chemin d'un fichier css,js,png ... Ex: $this->getPath('css/style.css');
     * @param $filename
     */
	public function getPath($filename)
	{
		//  echo BASE_URL.VIEW_WEBROOT.DS.$filename;
		echo BASE_URL.VIEW_WEBROOT.'/'.$filename;
	}

    /**
     * Inclut le fichier d'entête. A utiliser dans les fichiers de vue
     */
	public function getHeader()
	{
		extract($this->_vars);
		include VIEW_ROOT.DS.'header.php';
	}

    /**
     * Inclut le fichier de pied de page. A utiliser dans les fichiers de vue
     */
	public function getFooter()
	{
		extract($this->_vars);
		include VIEW_ROOT.DS.'footer.php';
	}

    /**
     * Récupère l'url de la page d'accueil
     */
	public function getHomeUrl()
	{
		echo BASE_URL;
	}

    /**
     * Connecte l'utilisateur connaissant un tableau d'infos
     * @param $userInfos
     */
	public function setUserConnected($userInfos)
	{
		$_SESSION['connected'] = true;
		$_SESSION['user_infos'] = $userInfos;
    }

    /**
     * Déconnecte l'utilisateur
     */
	public function setUserDisconnected()
	{
		$_SESSION['connected'] = false;
		$_SESSION['user_infos'] = array();
	}

    /**
     * L'utilisateur est-il connecté ?
     * @return bool
     */
	public function isUserConnected()
	{
		return (isset($_SESSION['connected']) && $_SESSION['connected'] == true) ? true : false;
	}

    /**
     *  Récupère les informations de l'utilisateur (stocké en session)
     * @param $key
     * @return mixed
     */
	public function getUserInfo($key)
	{
		return (isset($_SESSION['user_infos']) == true ? $_SESSION['user_infos'][$key] : null);
	}

    /**
     * Définit les informations de l'utilisateur (stocké en session)
     * @param $key
     * @param $value
     */
	public function setUserInfo($key, $value)
	{
		$_SESSION['user_infos'][$key] = $value;
	}

    /**
     * Permet de définir quelle page est active dans la barre de navigation
     * @param $title
     * @param $link
     * @param $name
     * @param $floatPos
     */
    public function setActiveLinkNav($title, $link, $name, $floatPos='')
    {
        $active = '<span class="active_page"><a style="text-decoration:none;color:#F0F8FF;cursor:default;" href="' . $link . '">'.$name.'</a></span></li>';
        $inactive = '<a href="' . $link . '">'.$name.'</a></li>';
        $floatLeft = '<li style="float:left;">';
        $floatRight = '<li style="float:right;">';

        if($title == 'Profil' && $floatPos == 'left') {
            echo $floatLeft . $active;
        }
        else if ($floatPos == 'left') {
            echo $floatLeft.$inactive;
        }
        if($title == 'Administration' && $floatPos == 'right') {
            echo $floatRight . $active;
        }
        else if($title == 'Administration des textes' && $floatPos == 'right') {
            echo $floatRight . $active;
        }
        else if($title == 'Administration des utilisateurs' && $floatPos == 'right') {
            echo $floatRight . $active;
        }
        else if ($floatPos == 'right') {
            echo $floatRight.$inactive;
        }
    }

    /**
     * Permet l'affichage d'une page active au niveau des boutons du menu
     * @param $title
     */
    public function setActiveLinkMenu($title)
    {
        // accueil
        if($title == 'Accueil') {
            echo '<li><a style="border-bottom: 3px solid #E68C2D;" href="' .BASE_URL.'">Accueil</a></li>';
        } else {
            echo '<li><a href="' .BASE_URL. '">Accueil</a></li>';
        }
        // annotation
        if($title == 'Selectionner un texte') {
            echo '<li><a style="border-bottom: 3px solid #E68C2D; cursor: default;" href="' .BASE_URL. '/text/selection/">Annoter<span class="info_bulle">Selectionner d\'abord un texte</span></a></li>';
        }
        else if ($title == 'Annoter un texte') {
            echo '<li><a style="border-bottom: 3px solid #E68C2D;" href="' .BASE_URL. '/text/selection/">Annoter</a></li>';
        }
        else {
            if ($this->isUserConnected() ) {
                echo '<li><a href="' . BASE_URL . '/text/selection/">Annoter</a></li>';
            }
        }
        // manuel
        if($title == 'Manuel') {
            echo '<li><a style="border-bottom: 3px solid #E68C2D;" href="' .BASE_URL. '/user/manuel/">Manuel</a></li>';
        } else {
            echo '<li><a href="' .BASE_URL. '/user/manuel/">Manuel</a></li>';
        }
        // a propos
        if($title == 'A propos') {
            echo '<li><a style="border-bottom: 3px solid #E68C2D;" href="' .BASE_URL. '/user/about/">A propos</a></li>';
        } else {
            echo '<li><a href="' .BASE_URL. '/user/about/">A propos</a></li>';
        }
    }
}