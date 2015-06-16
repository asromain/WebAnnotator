</head>
<body>
    <div id ="header_wrapper">
        <header>
            <div id="gestion">
                <div id="nav_gestion">
                    <ul>
                        <?php
                        // affichage d'un message et du lien profil si l'utilisateur est connecté
                        // affichage du lien administration et deconnexion
                        if ($this->isUserConnected() )
                        {
                            echo '<li style="float: left; padding-left:20px;"><a href="#"><span style="color:#29C46B;">Bonjour '.ucfirst($this->getUserInfo('pseudo')).' !</span></a></li>';
                            $link_profile = BASE_URL."/user/profile/?user=".$this->getUserInfo('pseudo');
                            $this->setActiveLinkNav($title, $link_profile, 'Editer profil', 'left');
                            ?>
                            <li style="float:right;"><a href="<?php $this->getHomeUrl(); ?>/user/logout/">Deconnexion</a></li>
                            <?php
                            $link_admin = BASE_URL."/administration/";
                            if($_SESSION['user_infos']['droit'] == "ARW" || $_SESSION['user_infos']['droit'] == "MASTER") {
                                $this->setActiveLinkNav($title, $link_admin, 'Administration', 'right');
                            }

                            // afficher un message pour se reconnecter dans le cas ou la permission d'acces a été modifié par un administrateur
                            $this->loadModel("user");
                            $droit = $this->getModel()->getUserInfo($_SESSION['user_infos']['pseudo'], 'permission');
                            if($droit != $_SESSION['user_infos']['droit']) { ?>
                                <li><a style="color:red;cursor:default;" href="#">
                                    Permission modifiée. Veuillez vous reconnecter svp <i style="margin-left:20px" class="fa fa-arrow-right"></i>
                                </a></li>
                            <?php }
                        }
                        // sinon on affiche les liens pour se connecter ou s'enregistrer
                        else { ?>
                            <li><a href="<?php $this->getHomeUrl(); ?>/user/login/">Connexion</a></li>
                            <li><a href="<?php $this->getHomeUrl(); ?>/user/signup/">Inscription</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div id="menu_wrapper">
                <div id="logo_site">
                    <a href="<?php $this->getHomeUrl(); ?>">
                        <h1><span class="lettrine_orange">W</span>eb<span class="lettrine_orange">A</span>nnotator</h1>
                    </a>
                </div>
                <nav>
                    <ul>
                        <?php $this->setActiveLinkMenu($title); ?>
                    </ul>
                </nav>
            </div>
        </header>
    </div>
    <div id="section_wrapper">
        <section>
            <div id="container">