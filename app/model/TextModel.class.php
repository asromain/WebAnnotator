<?php


/**
 * Class TextModel
 */
class TextModel extends Model
{

    /**
     * Retourne les infos d'un texte connaissant son identifiant
     * @param $textIdentifier
     * @return object
     */
	public function getTextById($textIdentifier)
	{
		return $this->query(
			"SELECT ?identifier ?title ?date ?publisher WHERE { 
				<".$this->_baseDescURI."$textIdentifier> dc11:identifier ?identifier.
				<".$this->_baseDescURI."$textIdentifier> dc11:title ?title.
				<".$this->_baseDescURI."$textIdentifier> dc11:date ?date.
				<".$this->_baseDescURI."$textIdentifier> dc11:publisher ?publisher
			}"
		);
	}

    /**
     * Retourne les infos de tous les textes
     * @return object
     */
	public function getAllText()
	{
		return $this->query(
			"SELECT ?title ?creator ?subject ?publisher ?date ?type ?format ?identifier ?source ?language WHERE { 
				?textIdentifier dc11:title ?title ;
								dc11:creator ?creator ;
								dc11:subject ?subject ;
								dc11:publisher ?publisher ;
								dc11:date ?date ;
								dc11:type ?type ;
								dc11:format ?format ;
								dc11:identifier ?identifier ;
								dc11:source ?source ;
								dc11:language ?language .
			}"
		);
	}

	/**
     * Insert toutes les infos d'un texte dans le graphe
     * @param $title
     * @param $author
     * @param $keyWord
     * @param $publisherURI
     * @param $timestamp
     * @param $timestamp
     * @param $source
     * @param $language
     * @return mixed
     */
	public function insertText($title, $author, $keyWord, $publisherURI, $timestamp, $source, $language)
	{
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:title', $title);
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:creator', $author);
//        foreach($keyWord as $val) {
		    $triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:subject', $keyWord);
//        }
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:publisher', $publisherURI);
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:date', $timestamp);
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:type', 'text');
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:format', 'text'); // a voir text/html
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:identifier', 'text_'.$timestamp);
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:source', $source);
		$triples[] = $this->constructTriple($this->_baseDescURI.'text_'.$timestamp, 'dc11:language', $language);
		return $this->insert($triples);
	}

}