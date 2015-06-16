<?php


/**
 * Class AnnotationModel
 */
class AnnotationModel extends Model
{

    /**
     * Préfix Open Annotation
     */
	private $_oa;

    /**
     * Constructeur : appel le constructeur parent et set les membres
     */
	public function __construct()
	{
		parent::__construct();

		// on ajoute les prefix utilisés seulement par cette classe
		$this->_oa = 'http://www.w3.org/ns/oa#';
		EasyRdf_Namespace::set('oa', $this->_oa);
	}

    /**
     * Récupere toutes les annotations connaissant l'identifiant d'un texte
     * @param $textIdentifier
     * @return object
     */
	public function getAllAnnotationFromTextId($textIdentifier)
	{
		return $this->query(
			"SELECT ?annotURI ?annotatedAt ?annotatedBy ?target ?body WHERE {
				?annotURI oa:annotatedAt ?annotatedAt;
					oa:annotatedBy ?annotatedBy;
					oa:hasTarget ?target;
					oa:hasBody ?body;
					oa:hasSource '$textIdentifier' .
			}"
		);
	}

    /**
     * Récupere toutes les infos d'une annotation connaissant l'identifiant de cette annotation
     * @param $identifier
     * @param $textIdentifier
     * @return object
     */
	public function getAnnotationFromId($identifier, $textIdentifier)
	{
		return $this->query(
			"SELECT ?annotatedAt ?annotatedBy ?target ?body WHERE {
				<".$this->_baseDescURI."annot_$identifier> oa:annotatedAt ?annotatedAt ;
					oa:annotatedBy ?annotatedBy ;
					oa:hasTarget ?target ;
					oa:hasBody ?body ;
					oa:hasSource '$textIdentifier' .
			}"
		);
	}

    /**
     * Insert toutes les infos d'une annotation dans le graphe
     * @param $timestamp
     * @param $userURI
     * @param $target
     * @param $content
     * @param $textIdentifier
     * @return object
     */
	public function insertAnnotation($timestamp, $userURI, $target, $content, $textIdentifier)
	{
		$triples[] = $this->constructTriple($this->_baseDescURI.'annot_'.$timestamp, 'oa:annotatedAt', $timestamp.'');
		$triples[] = $this->constructTriple($this->_baseDescURI.'annot_'.$timestamp, 'oa:annotatedBy', $userURI);
		$triples[] = $this->constructTriple($this->_baseDescURI.'annot_'.$timestamp, 'oa:hasTarget', $target);
		$triples[] = $this->constructTriple($this->_baseDescURI.'annot_'.$timestamp, 'oa:hasBody', $content);
		$triples[] = $this->constructTriple($this->_baseDescURI.'annot_'.$timestamp, 'oa:hasSource', ''.$textIdentifier.''); // TODO TERC-12

		return $this->insert($triples);
	}

    /**
     * Supprime tous les triplet d'une annotation connaissant l'identifiant de cette annotation
     * @param $timestamp
     * @param $textIdentifier
     * @return bool
     */
    public function deleteAnnotation($timestamp, $textIdentifier) {
        
        $resultAnnot = $this->getAnnotationFromId($timestamp, $textIdentifier);
		
		$arrobj = new ArrayObject($resultAnnot);
		$i = $arrobj->getIterator();
		if ($i->valid()) {
			$annotInfo = array(
							'annotatedAt' => $i->current()->annotatedAt->getValue(),
							'annotatedBy' => $i->current()->annotatedBy->getValue(),
							'target' => $i->current()->target->getValue(),
							'body' => $i->current()->body->getValue(),
							'source' => $_SESSION['textIdentifier']
						);
		}
		else
		{
			return false;
		}


        $triple = '<'.$this->_baseDescURI.'annot_'.$timestamp.'> <'.$this->_oa.'annotatedAt> "'.$annotInfo['annotatedAt'].'"';
        $this->delete($triple);
        $triple = '<'.$this->_baseDescURI.'annot_'.$timestamp.'> <'.$this->_oa.'annotatedBy> "'.$annotInfo['annotatedBy'].'"';
        $this->delete($triple);
        $triple = '<'.$this->_baseDescURI.'annot_'.$timestamp.'> <'.$this->_oa.'hasTarget> "'.$annotInfo['target'].'"';
        $this->delete($triple);
        $triple = '<'.$this->_baseDescURI.'annot_'.$timestamp.'> <'.$this->_oa.'hasBody> "'.$annotInfo['body'].'"';
        $this->delete($triple);
        $triple = '<'.$this->_baseDescURI.'annot_'.$timestamp.'> <'.$this->_oa.'hasSource> "'.$annotInfo['source'].'"';
        $this->delete($triple);

        return true;
    }

    /**
     * Met a jour le body d'une annotation connaissant l'identifiant de cette annotation et le nouveau body
     * @param $timestamp
     * @param $textIdentifier
     * @param $newBody
     * @return bool
     */
    public function updateAnnotation($timestamp, $textIdentifier, $newBody) {
        
        $resultAnnot = $this->getAnnotationFromId($timestamp, $textIdentifier);
		
		$arrobj = new ArrayObject($resultAnnot);
		$i = $arrobj->getIterator();
		if ($i->valid()) {
			$annotInfo = array(
							'annotatedAt' => $i->current()->annotatedAt->getValue(),
							'annotatedBy' => $i->current()->annotatedBy->getValue(),
							'target' => $i->current()->target->getValue(),
							'body' => $i->current()->body->getValue(),
							'source' => $_SESSION['textIdentifier']
						);
		}
		else
		{
			return false;
		}


        $triple = '<'.$this->_baseDescURI.'annot_'.$timestamp.'> <'.$this->_oa.'hasBody> "'.$annotInfo['body'].'"';
        $this->delete($triple);

		$triples[] = $this->constructTriple($this->_baseDescURI.'annot_'.$timestamp, 'oa:hasBody', $newBody);

		$this->insert($triples);
		
        return true;
    }


}