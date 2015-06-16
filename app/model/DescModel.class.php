<?php


/**
 * Class DescModel
 */
class DescModel extends Model
{

    /**
     * Recupere tous les triplets associé à une URI
     * @param $identifier
     * @return object
     */
	public function getAllAttributeFromId($identifier) 
	{
		return $this->query(
			"SELECT ?predicate ?object WHERE { 
				<".$this->_baseDescURI."$identifier> ?predicate ?object.
			}"
		);
	}

}