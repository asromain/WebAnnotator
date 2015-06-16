<?php


/**
 * Class UserModel
 */
class UserModel extends Model
{

    /**
     * Permet d'obtenir les info de l'utilisateur par pseudo
     * @param $pseudo
     * @return object
     */
	public function getUserInfoByPseudo($pseudo)
	{
		return $this->query(
			"SELECT ?subject ?mdp ?droit WHERE {
				?subject foaf:nick '$pseudo' ;
						 foaf:sha1 ?mdp ;
						 foaf:permission ?droit .
			}"
		);
	}

    /**
     * Permet l'insertion d'un nouvel utilisateur dans la base de donnée
     * @param $pseudo
     * @param $mdp
     * @param $droit
     */
	public function insertUser($pseudo, $mdp, $droit)
	{
		$triples[] = $this->constructTriple($this->_baseDescURI.'user_'.$pseudo, 'foaf:nick', $pseudo);
		$triples[] = $this->constructTriple($this->_baseDescURI.'user_'.$pseudo, 'foaf:permission', $droit);
		$triples[] = $this->constructTriple($this->_baseDescURI.'user_'.$pseudo, 'foaf:sha1', $mdp);
		$this->insert($triples);
	}

    /**
     * Permet d'inserer un triplet dans l'édition de profil
     * @param $pseudo
     * @param $object
     * @param $predicate
     */
    public function insertInfo($pseudo, $object, $predicate) {
        $foaf = 'foaf:'.$predicate;
        $triples[] = $this->constructTriple($this->_baseDescURI.'user_'.$pseudo, $foaf, $object);
        $this->insert($triples);
    }

    /**
     * Permet de supprimer un triplet dans l'édition de profil
     * @param $pseudo
     * @param $object
     * @param $predicate
     */
    public function deleteInfo($pseudo, $object, $predicate) {
        $foaf = 'http://xmlns.com/foaf/0.1/'.$predicate;
        $triple = '<'.$this->_baseDescURI.'user_'.$pseudo.'> <'.$foaf.'> "'.$object.'"';
        // var_dump(htmlentities($triple));
        $this->delete($triple);
    }

    /**
     * Supprimer un utilisateur par son pseudo
     * @param $pseudo
     */
    public function deleteUser($pseudo)
    {
        $foaf = 'http://xmlns.com/foaf/0.1/';
        $nick = 'nick';
        $sha1 = 'sha1';
        $givenName = 'givenName';
        $familyName = 'familyName';
        $permission = 'permission';

        $triple = '<'.$this->_baseDescURI.'user_'.$pseudo.'> <'.$foaf.$nick.'> "'.$this->getUserInfo($pseudo, $nick).'"';
        $this->delete($triple);

        $triple = '<'.$this->_baseDescURI.'user_'.$pseudo.'> <'.$foaf.$sha1.'> "'.$this->getUserInfo($pseudo, $sha1).'"';
        $this->delete($triple);

        $triple = '<'.$this->_baseDescURI.'user_'.$pseudo.'> <'.$foaf.$permission.'> "'.$this->getUserInfo($pseudo, $permission).'"';
        $this->delete($triple);

        if($this->infoAlreadyExist($pseudo, $givenName)) {
            $triple = '<'.$this->_baseDescURI.'user_'.$pseudo.'> <'.$foaf.$givenName.'> "'.$this->getUserInfo($pseudo, $givenName).'"';
            $this->delete($triple);
        }
        if($this->infoAlreadyExist($pseudo, $familyName)) {
            $triple = '<'.$this->_baseDescURI.'user_'.$pseudo.'> <'.$foaf.$familyName.'> "'.$this->getUserInfo($pseudo, $familyName).'"';
            $this->delete($triple);
        }
    }

    /**
     * Savoir si le nom existe déjà dans la base de donnée connaissant le pseudo
     * @param $pseudo
     * @param $predicate
     * @return bool
     */
    public function infoAlreadyExist($pseudo, $predicate)
    {
        $foaf = 'foaf:'.$predicate;
        $result = $this->query(
            "SELECT ?info WHERE {
			    <".$this->_baseDescURI."user_$pseudo> ".$foaf." ?info
		    }"
        );

        $arrobj = new ArrayObject($result);
        $i = $arrobj->getIterator();

        if($i->valid()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Récupération d'une info de l'utilisateur connaissant le pseudo
     * @param $pseudo
     * @param $predicate
     */
    public function displayInfo($pseudo, $predicate)
    {
        $result = $this->query(
            "SELECT ?name WHERE {
                <".$this->_baseDescURI."user_$pseudo> foaf:".$predicate." ?name
            }"
        );

        $arrobj = new ArrayObject($result);

        // iteration sur les resultats
        for($i = $arrobj->getIterator(); $i->valid(); $i->next()) {
            echo ucfirst($i->current()->name->getValue());
        }
    }

    /**
     * Fonction retourne les info de l'utilisateur connaissant son pseudo
     * @param $pseudo
     * @param string $predicate
     * @return mixed
     */
    public function getUserInfo($pseudo, $predicate) {
        $result = $this->query(
            "SELECT ?name WHERE {
			    <".$this->_baseDescURI."user_$pseudo> foaf:".$predicate." ?name
		    }"
        );
        $arrobj = new ArrayObject($result);
        for($i = $arrobj->getIterator(); $i->valid(); $i->next()) {
            return $i->current()->name->getValue();
        }
        return '';
    }


    /**
     * Permet d'afficher les informations relatives d'un utilisateur selon
     * un certain predicat
     * @param $predicate
     * @return mixed
     */
    public function getAllPseudo($predicate = 'nick')
    {
        $result = $this->query(
            "SELECT ?pseudo ?name WHERE {
			    ?pseudo foaf:".$predicate." ?name
		    }"
        );
        $arrobj = new ArrayObject($result);
        return $arrobj;
    }

    /**
     * Permet d'obtenir toutes les infos de tous les utilisateurs
     * @return object
     */
    public function getAllUsersInfos()
    {
        return $this->query(
            "SELECT ?subject ?pseudo ?mdp ?droit WHERE {
                ?subject foaf:nick ?pseudo ;
                         foaf:sha1 ?mdp ;
                         foaf:permission ?droit .
            }"
        );
    }



}