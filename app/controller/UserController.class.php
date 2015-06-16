<?php


/**
 * Class UserController
 */
class UserController extends Controller
{
	
    /**
     * Redirige vers la page d'accueil
     * @param null $params
     */
	public function index($params = null)
	{
		header('Location: '.BASE_URL);
	}

	/**
	 * Methode pour se connecter en tant que membre
	 * @param null $params
	 */
	public function login($params = null)
	{
		// $this->loadModel("index");
		// $this->_model->query();
		// On set la variable a afficher sur dans la vue
		$this->set('title', 'Log in');
		// On fait le rendu de la vue login.php
		$this->render('login');
	}

	/**
	 * Methode pour s'enregistrer en tant que membre
	 * @param null $params
	 */
	public function signup($params = null)
	{
		// On set la variable a afficher sur dans la vue
		$this->set('title', 'Sign up');
		// On fait le rendu de la vue signup.php
		$this->render('signup');
	}

    /**
     * Methode pour visualiser le manuel de l'application
     */
    public function manuel()
    {
        // On set la variable a afficher sur dans la vue
        $this->set('title', 'Manuel');
        // On fait le rendu de la vue signup.php
        $this->render('manuel');
    }

    /**
     * Methode pour visualiser la page a propos
     */
    public function about()
    {
        // On set la variable a afficher sur dans la vue
        $this->set('title', 'A propos');
        // On fait le rendu de la vue signup.php
        $this->render('about');
    }

    /**
	 * Permet de savoir si l'utilisateur existe bien dans la base
	 * @param null $params
	 */
	public function check($params = null)
	{
		// test si les champs pseudo et mdp sont bien remplis
		if(isset($_POST['pseudo']) && isset($_POST['mdp']))
		{
			$pseudo = strtolower($_POST['pseudo']);
			$mdp = sha1($_POST['mdp']);

			$this->loadModel('user');

			// on test si le couple pseudo mdp existe dans la base
			// TODO id pour membre
			$result = $this->getModel()->getUserInfoByPseudo($pseudo);

			$arrobj = new ArrayObject($result);
			// iteration sur les resultats
			// for($i = $arrobj->getIterator(); $i->valid(); $i->next()) {
			//  echo $i->key() . ' ===> ' . $i->current()->pseudo->getValue() . '<br>';
			// }

			$i = $arrobj->getIterator();

			if($i->valid()) {
				// si le couple pseudo et mot de passe corresponde a ceux dans la base de donnee
				if($i->current()->mdp->getValue() == $mdp) {
					// $_SESSION['pseudo'] = $pseudo;
					$this->setUserConnected(array(
												'pseudo' => $pseudo,
												'URI' => $i->current()->subject->getUri(),
                                                'droit' => $i->current()->droit->getValue()
												));
					header('Location: '.BASE_URL.'/index/index/');
				}
				else {
					header('Location: '.BASE_URL.'/user/login/?connexion=0');
				}
			}
			else {
				header('Location: '.BASE_URL.'/user/login/?connexion=0');
			}
		}
	}

	/**
	 * Methode pour inserer un nouvel utilisateur
	 * @param null $params
	 */
	public function insert($params = null)
	{
        $error = '';
		if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['mdp_conf']))
		{
            $pseudo = strtolower($_POST['pseudo']);
            $droit = $_POST["droit"];
            $mdp = sha1($_POST['mdp']);
            $mdp_conf = sha1($_POST['mdp_conf']);

            // pseudo
            if(strlen($pseudo) < 4) {
                $error = 'short_pseudo';
            }
            else if(!ctype_alnum($pseudo)) {   // test si le pseudo contient des caractères autres que des chiffres et lettres
                $error = 'invalid_pseudo';
            }
            // mot de passe
            else if($mdp != $mdp_conf) {
                $error = 'mdp';
            }
            else if (strlen($_POST['mdp']) < 6) {  // TODO valeur à baisser si casse couille :)
                $error = 'short_mdp';
            }

            // si pas d'erreur on va tester si le pseudo existe deja
            if($error == '')
            {
                $this->loadModel('user');
                $result = $this->getModel()->infoAlreadyExist($pseudo, 'nick');

                if ($result) {
                    // si le pseudo existe déja on redirige pour s'incrire a nouveau
                    header('Location: ' . BASE_URL . '/user/signup/?inscription=pseudo_exist');
                } else {
                    $this->getModel()->insertUser($pseudo, $mdp, $droit);
                    header('Location: ' . BASE_URL . '/user/login/?inscription=1');
                }
            }
            else {
                header('Location: ' . BASE_URL . '/user/signup/?inscription='.$error);
            }
		}
	}

	/**
	 * Methode pour deconnecter l'utilisateur
	 * @param null $params
	 */
	public function logout($params = null)
	{
		// Suppression des variables de session et de la session
		$this->setUserDisconnected();

		// Suppression des cookies de connexion automatique
		setcookie('pseudo', '');
		setcookie('mdp', '');

		header('Location: '.BASE_URL.'/index/index/');
	}

	/**
	 * Methode permettant d'afficher son profil
	 * @param null $params
	 */
	public function profile($params = null)
	{
		$this->loadModel("user");
		// On set la variable a afficher sur dans la vue
		$this->set('title', 'Profil');
		// On fait de rendu de la vue login.php
		$this->render('profile');
	}

	/**
	 * Methode permettant d'editer les informations de son profil
	 * @param null $param
	 */
	public function editProfile($param = null)
	{
        $this->loadModel('user');

        // si aucun champ n'est renseigné
        if($_POST['nom'] == '' && $_POST['prenom'] == '' && $_POST['mdp'] == '') {
            header('Location: '.BASE_URL.'/user/profile/?erreur=1');
        }
        // si le nom est renseigné
		if(isset($_POST['nom']) && $_POST['nom'] != '')
		{
            $pseudo = $this->getUserInfo("pseudo");
            $nom = strtolower($_POST['nom']);

            if($this->getModel()->infoAlreadyExist($pseudo, 'familyName'))
            {
                $oldNom = $this->getModel()->getUserInfo($pseudo, 'familyName');
                $this->getModel()->deleteInfo($pseudo, $oldNom, 'familyName');
                $this->getModel()->insertInfo($pseudo, $nom, 'familyName');
                header('Location: '.BASE_URL.'/user/profile/?info=1');

            } else {
                $this->getModel()->insertInfo($pseudo, $nom, 'familyName');
                header('Location: '.BASE_URL.'/user/profile/?newinfo=1');
            }
		}
        // si le prénom est renseigné
        if(isset($_POST['prenom']) && $_POST['prenom'] != '')
        {
            $pseudo = $this->getUserInfo("pseudo");
            $prenom = strtolower($_POST['prenom']);

            if($this->getModel()->infoAlreadyExist($pseudo, 'givenName'))
            {
                $oldPrenom = $this->getModel()->getUserInfo($pseudo, 'givenName');
                $this->getModel()->deleteInfo($pseudo, $oldPrenom, 'givenName');
                $this->getModel()->insertInfo($pseudo, $prenom, 'givenName');
                header('Location: '.BASE_URL.'/user/profile/?info=1');

            } else {
                $this->getModel()->insertInfo($pseudo, $prenom, 'givenName');
                header('Location: '.BASE_URL.'/user/profile/?newinfo=1');
            }
        }
        // si le mdp est renseigné
        if(isset($_POST['mdp']) && $_POST['mdp'] != '')
        {
            $pseudo = $this->getUserInfo("pseudo");
            $mdp = sha1($_POST['mdp']);

            if( strlen($_POST['mdp']) >= 6 )
            {
                $oldMdp = $this->getModel()->getUserInfo($pseudo, 'sha1');

                $this->getModel()->deleteInfo($pseudo, $oldMdp, 'sha1');
                $this->getModel()->insertInfo($pseudo, $mdp, 'sha1');
                header('Location: ' . BASE_URL . '/user/profile/?info=1');
            }
            else {
                header('Location: '.BASE_URL.'/user/profile/?mdp=1');
            }

        }
	}


    /**
     * Methode pour visualiser la page d'administration user
     */
    public function administration()
    {
		// user connecter
		if (!$this->isUserConnected()) {
			header('Location: '.BASE_URL);
			return ;
		}
		// user admin
		if ($this->getUserInfo('droit') != 'ARW' && $this->getUserInfo('droit') != 'MASTER') {
			header('Location: '.BASE_URL);
			return ;
		}

        $this->loadModel('user');

        $result = $this->getModel()->getAllUsersInfos();

		$arrobj = new ArrayObject($result);
        for($i = $arrobj->getIterator(); $i->valid(); $i->next())
        {
        	$usersInfos[] = array(
        		'subject' => $i->current()->subject->getUri(),
        		'pseudo' => $i->current()->pseudo->getValue(),
        		'givenName' => $this->getModel()->getUserInfo($i->current()->pseudo->getValue(), 'givenName'),
        		'familyName' => $this->getModel()->getUserInfo($i->current()->pseudo->getValue(), 'familyName'),
        		'droit' => $i->current()->droit->getValue()
        		);
        }

    	$this->set('userPseudo', $this->getUserInfo('pseudo'));

    	$this->set('usersInfos', $usersInfos);

        // On set la variable a afficher sur dans la vue
        $this->set('title', 'Administration des utilisateurs');
        // On fait le rendu de la vue signup.php
        $this->render('adminUser');
    }



    /**
     * Permet de mettre des permissions aux utilisateurs dans la partie
     * administration. TODO page si beaucoup d'utilisateur + fonction recherche
     * @param null $param
     */
    public function permission($param = null)
    {
        $this->loadModel('user');

        if(isset($_POST["pseudo"]) && $_POST["pseudo"] != '')
        {
            $pseudo = $_POST["pseudo"];
            $droit = $_POST["droit"];

            if($pseudo != $_SESSION['user_infos']['pseudo'] ) {
                $oldPerm = $this->getModel()->getUserInfo($pseudo, 'permission');

                $this->getModel()->deleteInfo($pseudo, $oldPerm, 'permission');
                $this->getModel()->insertInfo($pseudo, $droit, 'permission');
            }

            header('Location: '.BASE_URL.'/user/administration/?'.$pseudo.'='.$droit);
        }
    }


    public function deleteUser($param = null)
    {
        $this->loadModel('user');

        if(isset($_POST["pseudo"]) && $_POST["pseudo"] != '')
        {
            $pseudo = $_POST["pseudo"];

            $this->getModel()->deleteUser($pseudo);
            header('Location: '.BASE_URL.'/user/administration/?'.$pseudo.'=1');
        }
    }
}