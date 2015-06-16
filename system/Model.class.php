<?php

set_include_path(get_include_path() . PATH_SEPARATOR . LIB_ROOT.'/easyrdf/lib/');
require_once "EasyRdf.php";

/**
 * Class Model
 */
class Model
{
    /**
     * GraphStore
     */
	protected $_graphstore;

    /**
     * SPARQL
     */
	protected $_sparql;

    /**
     * delete url
     */
	protected $_deleteURL;

    /**
     * database name
     */
	protected $_dbName;

    /**
     * descURI
     */
	protected $_baseDescURI;

    /**
     * Constructeur
     */
	public function __construct()
	{
		$confRedstore = Config::getRedstoreConfig();

		$this->_sparql = new EasyRdf_Sparql_Client($confRedstore['sparql']);
		$this->_graphstore = new EasyRdf_GraphStore($confRedstore['graphstore']);
		$this->_deleteURL = $confRedstore['delete'];
		$this->_dbName = $confRedstore['databaseName'];
		$this->_baseDescURI = BASE_URL.$confRedstore['descURI'];

	}


    /**
     * Execute une query SPARQL SELECT et renvoie le résultat
     * @param $queryStr
     * @return object
     */
	public function query($queryStr) {
		try {
			// echo "model query";
			return $this->_sparql->query($queryStr);
		} catch (Exception $e) {
			echo "query";
			die($e->getMessage());
		}
	}

/*
	public function update($queryStr) {
		try {
			return $this->_sparql->update($queryStr);
		} catch (Exception $e) {
			echo "update";
			die($e->getMessage());
		}
	}
*/
    /**
     * Supprime un triplet du graph
     * @param $triple
     * @return mixed
     */
	public function delete($triple) {
		try {
			$post_data_array = array(
				'content' => $triple,
				'content-type' => 'ntriples',
				'graph' => $this->_dbName,
				'base-uri' => ''
			);
			$post_data = http_build_query($post_data_array);

			$result = $this->cUrlPost($this->_deleteURL, $post_data);

			return $result;
		} catch (Exception $e) {
			echo "delete";
			die($e->getMessage());
		}
		// saveGraph();
	}

    /**
     * Insert des triplets construit dans le graph rdf
     * @param $triples
     * @return mixed
     */
	public function insert($triples) {
		try {
			$graph = new EasyRdf_Graph();
			foreach ($triples as $triple){
				$graph->add($triple['subject'], $triple['predicate'], $triple['object']);
			}
			return $this->_graphstore->insert($graph, $this->_dbName);
		} catch (Exception $e) {
			echo "insert";
			die($e->getMessage());
		}
		// saveGraph();
	}



    /**
     * Construit un triplet RDF pour l'insertion
     * @param $subject
     * @param $predicate
     * @param $object
     * @return array
     */
	public function constructTriple($subject, $predicate, $object)
	{
		return array(
						'subject'	=>	$subject,
						'predicate'	=>	$predicate,
						'object'	=>	$object
					);
	}

    /**
     * Renvoie la base de l'URI de description
     * @return string
     */
	public function getBaseDescURI()
	{
		return $this->_baseDescURI;
	}


    /**
     * Sauvegarde le graphe RDF depuis redstore sur le disque dans data/
     */
	public function saveGraph() {
		$timestamp = time();
		file_put_contents("data/data-".$timestamp.".rdf", fopen($this->_dbName."?default&format=rdfxml", 'r'));
		// file_put_contents("data/data.rdf", fopen("http://localhost:8080".$this->_dbName."?default&format=rdfxml", 'r'));
		// formats qui fonctionnent
		// format:rdfxml, format:turtle, 
	}

/*
	// useless
	public function loadGraph() {

		$post_data_array = array(
				'uri' => BASE_URL.'/data/data.rdf',
				'graph' => 'http://localhost:8080'.$this->_dbName,
				'base-uri' => ''
			);

		$post_data = http_build_query($post_data_array);

		$urlLoad = 'http://localhost:8080/load';

		return $result = $this->cUrlPost($urlLoad, $post_data);

	}
*/


    /**
     * Effectue un curl avec POST connaissant l'url d'envoie et les parametres de post
     * @param $url
     * @param $post_data
     * @return string
     */
	private function cUrlPost($url, $post_data) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		$response = curl_exec($ch);
		// echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // code retour
		curl_close ($ch);
		return $response;
	}


}