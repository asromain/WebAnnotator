<?php

/**
 * Class AutoClassLoader
 */
class AutoClassLoader
{

	/**
	 * _instance
	 */
	private static $_instance = null;

	/**
	 * Constructeur
	 */
	private function __construct()
	{
		set_include_path(get_include_path() . PATH_SEPARATOR . CONTROLLER_ROOT);
		set_include_path(get_include_path() . PATH_SEPARATOR . MODEL_ROOT);
		set_include_path(get_include_path() . PATH_SEPARATOR . UTIL_ROOT);
		
		spl_autoload_extensions('.class.php');
		// pour chaque fichier a loader on appele $this->loadClasses($nomDuFichier)//sans extension
		spl_autoload_register(array($this, 'loadClasses'), false);
		
	}

	/**
	 * Load la classe en parametre
	 * @param $class
	 */
	private function loadClasses($class)
	{
		//on include le fichier de la classe
		if (file_exists(CONTROLLER_ROOT .'/' .$class.'.class.php'))
			require_once $class.'.class.php';
		elseif (file_exists(MODEL_ROOT .'/' .$class.'.class.php'))
			require_once $class.'.class.php';
		elseif (file_exists(UTIL_ROOT .'/' .$class.'.class.php'))
			require_once $class.'.class.php';

		// on load la classe
		spl_autoload(ucfirst($class));
	}

    /**
     * Singleton instancie la classe si elle ne l'est pas deja
     * @return object
     */
	public static function getInstance()
	{
		// singleton pour eviter deux includes
		if(is_null(self::$_instance)) 
		{
			self::$_instance = new AutoClassLoader();  
		}

		return self::$_instance;
	}
}

//on instancie notre loader.
AutoClassLoader::getInstance();
