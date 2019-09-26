<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 11.06.2019
 * Time: 15:51
 */
require_once '../vendor/autoload.php';

use Application\Controllers\ApplicationController;

$app = new ApplicationController();
$app->Start();