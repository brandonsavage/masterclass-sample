<?php

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('../lib'));

$config = require_once('../config/config.php');
require_once 'MasterController.php';

$framework = new MasterController($config);
echo $framework->execute();