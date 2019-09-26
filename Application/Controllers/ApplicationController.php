<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 17.06.2019
 * Time: 13:59
 */

namespace Application\Controllers;


use Application\Utils\MySQL;
use Bramus\Router\Router;

class ApplicationController extends BaseController
{
    public function Start(){
        date_default_timezone_set('Europe/Moscow');

        session_start();

        MySQL::$db = new \PDO('mysql:dbname=tastydb;host=127.0.0.1;charset=utf8',
            'fAdmin',
            '');

        $router = new Router();

        $router->setNamespace('Application\\Controllers');

        $router->set404(function (  ) {

            $this->json(200, array(
                'code' => 403,
                'message' => 'Ошибка в роутинге!',
                'data' => null
            ));
        });

        $routes = include_once '../Application/Models/PublicRoutes.php';

        foreach ($routes as $key => $path ){

            foreach ($path as $subKey => $value){

                $router->$key( $subKey , $value );

            }//foreach

        }//foreach

        $router->run();

    }//Start

}//ApplicationController