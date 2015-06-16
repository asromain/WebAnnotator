<?php


class Config
{
	/**
	 * Configuration de la connexion à la base de données
	 */
	private static $_redstore = array(
								'databaseName'	=>	'http://localhost:8080/data/database.rdf',
								'sparql'		=>	'http://localhost:8080/sparql',
								'delete'		=>	'http://localhost:8080/delete',
								'graphstore'	=>	'http://localhost:8080/data',
								'graphload'		=>	'http://localhost:8080/load',
								'descURI'		=>	'/desc/view/'                     // TODO propose /uri/view/ ??
							);

	 /**
	 * Récupère la configuration de la base de donnée redstore.
	 * 
	 * @return	array	Les configurations de redstore
	 */
	public static function getRedstoreConfig()
	{
		return self::$_redstore;
	}
}

