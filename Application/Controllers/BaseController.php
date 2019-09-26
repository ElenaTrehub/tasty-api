<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 19.06.2019
 * Time: 21:37
 */

namespace Application\Controllers;


use Application\Utils\Request;

class BaseController
{
    protected $request;

    public function __construct()
    {
        $this->request = new Request();
    }//__construct

    protected function json( $code , $data ){

        http_response_code($code);
        header('Content-type: application/json');
        echo json_encode($data); //  res.send();
        exit();

    }//json
}