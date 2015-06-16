<?php


/**
 * Class DescController
 */
class DescController extends Controller
{
	

	/**
	 * Ne fais rien
	 * @param null $params
	 */
	public function index($params = null)
	{
		
	}

	/**
	 * Affiche les infos relative à une URI
	 * @param null $params
	 */
	public function view($params = null)
	{
		$this->loadModel("desc");

		if (isset($params[0]) && $params[0] != '') {
			$identifier = $params[0];
		}
		else {
			echo "header Location : base url"; // TODO 
			return ;
		}

		$result = $this->getModel()->getAllAttributeFromId($identifier);

		$arrobj = new ArrayObject($result);

		for($i = $arrobj->getIterator(); $i->valid(); $i->next()) {
			$attributes[] = array(
								'predicate' => $i->current()->predicate->getUri(),
								'object' => $i->current()->object
								);// TODO pour bien faire il faudrais tester si objct est un literal ou pas
		}

		if (isset($attributes)) {
			$this->set('attributes', $attributes);
		}

		$this->set('subject', $this->getModel()->getBaseDescURI().$identifier);



		$this->set('title', "description d'une URI");

		$this->render('descriptionURI');
	}


}