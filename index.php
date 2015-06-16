<?php

require_once 'system/defineConstant.php';

require_once 'config/Config.class.php';
require_once 'system/AutoClassLoader.class.php'; // s'autoinstancie (singleton)
require_once 'system/Router.class.php';

new Router();