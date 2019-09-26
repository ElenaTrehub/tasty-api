<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 17.06.2019
 * Time: 14:53
 */

namespace Application\Controllers;



use Application\Services\JWTService;
use Application\Services\UserService;
use Bcrypt\Bcrypt;

class UserController extends BaseController
{
    public function addUser( ){

        $pattern = new patternConst();

        $userLogin = $this->request->GetPostValue('userLogin');
        if(!preg_match($pattern->LoginPattern,$userLogin)){
            $this->json(200,array(
                'code'=> 400,
                'message' => 'неверный логин',
                'data' => $userLogin
            ));
            return;
        }//if

        $userEmail = $this->request->GetPostValue('userEmail');
        if(!preg_match($pattern->EmailPattern,$userEmail)){
            $this->json(200,array(
                'code'=> 400,
                'message' => 'неверный Email',
                'data' => null
            ));
            return;
        }//if

        $userName = $this->request->GetPostValue('userName');
        if(!preg_match($pattern->NamesPattern, $userName)){
            $this->json(200,array(
                'code'=> 400,
                'message' => 'Не корректное Имя',
                'data' => null
            ));
            return;
        }//if





        $usrPassword = $this->request->GetPostValue('password');
        if(!preg_match($pattern->PasswordPattern,$usrPassword)){
            $this->json(200,array(
                'code'=> 400,
                'message'=> 'неверный пароль',
                'data' => null
            ));
            return;
        }//if

        $bcrypt = new Bcrypt();
        $bcrypt_version = '2y';
        $heshToken = $bcrypt->encrypt($userEmail,$bcrypt_version);

        $userService = new UserService();

        $statusUser = $pattern->statusUserNotVerificate;
        $roleUser = $pattern->roleUserRegistred;

        $result = $userService->addUser( $userLogin, $usrPassword, $userEmail, $userName,  $heshToken, $statusUser, $roleUser );

        if($result !== null){

            $message = new messageConst();

            $message->tuneTemplate($userLogin,$heshToken);
           // $mailres = mail($userEmail , $message->verificationSubject,$message->verificationTemplate,$message->header);

            $this->json(200, array(
                'code' => 200,
                'message' => 'Проверьте Ваш Email!',
                'data' => null
            ));

            //https://www.w3schools.com/php/php_ref_mail.asp
        }//if
        else{

            $this->json(200,array(
                'code'=> 403,
                'message' => 'Пользователь с такими данными уже есть!',
                'data' => null
            ));

        }//else


    }//addUser
    public function verificationUser(){
        $pattern = new patternConst();
        $userService = new UserService();

        $token = $this->request->GetGetValue('token');
        $statusUser = $pattern->statusUserActive;

        $userVer = $userService->verificationUser($token, $statusUser);


    }//verificationUser

    public function getCurrentUser(){

        $jwtService = new JWTService();
        $ip = $_SERVER["REMOTE_ADDR"];
        $result = $jwtService->getCurrentUserID($this->request->getRequestHeaders(), $ip);

        if($result) {
            $userService = new UserService();
            $user = $userService->getSingleUser($result['userID']);

            if($user){
                if($result['token']){
                    $res = array(
                        'user'=>$user,
                        'token'=>$result['token']
                    );
                    $this->json(200, array(
                        'code'=>200,
                        'message'=>'Добавление лота прошло успешно!',
                        'data'=>$res
                    ));
                }
                else{
                    $this->json(200, array(
                        'code'=>200,
                        'data'=>$user,
                        'message'=>'Пользователь авторизован!'
                    ));
                }


            }
            else{
                $this->json(200, array(
                    'code'=>400,
                    'data'=>null,
                    'message'=>'Ошибка сервера!'
                ));
            }

        }


    }//getCurrentUser
}//UserController