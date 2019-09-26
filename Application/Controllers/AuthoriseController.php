<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 17.06.2019
 * Time: 14:55
 */

namespace Application\Controllers;



use Application\Services\AuthService;
use Application\Services\IPService;
use Application\Services\JWTService;

class AuthoriseController extends BaseController
{
    public function authUserAction(){
        $authoriseService = new AuthService();

        $login = $this->request->GetPostValue('userLogin');
        $password =$this->request->GetPostValue('userPassword');

        $result = $authoriseService->LogIn($login, $password);

        if($result['code'] === 200){
            //$ip = $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : ($_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

            $ip = $_SERVER["REMOTE_ADDR"];
            $ipService = new IPService();
            $ipService->addUserIp($result['userID'], $ip);

            $jwtService = new JWTService();
            $jwtresult = $jwtService->handle($this->request, $result['userID'], $ip);

            $this->json(200, array(
                'code'=>200,
                'data'=>$jwtresult,
                'message'=>'Пользователь авторизован!'
            ));

        }//if
        else if($result['code'] === 400){
            $this->json(200, array(
                'code'=>400,
                'data'=>null,
                'message'=>'Пользователя с такими данными нет! Aвторизируйтесь!'
            ));
        }//else if

        else if($result['code'] === 401){
            $this->json(200, array(
                'code'=>400,
                'data'=>null,
                'message'=>'Пользователя с такими данными уже существует! Aвторизируйтесь!'
            ));
        }//else if

    }//authUserAction

}//AuthriseController