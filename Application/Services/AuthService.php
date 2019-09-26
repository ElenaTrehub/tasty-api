<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 17.06.2019
 * Time: 16:18
 */

namespace Application\Services;


use Application\Controllers\patternConst;
use Application\Utils\MySQL;
use Bcrypt\Bcrypt;

class AuthService
{
    public function LogIn( $login, $password){

        $bcrypt = new Bcrypt();

        //ищем пользователя
        $stm = MySQL::$db->prepare( "SELECT userID, userLogin, password, userEmail, userName, userStatusID, userRoleID FROM users WHERE userLogin = :login" );
        $stm->bindParam(':login', $login, \PDO::PARAM_STR);
        $stm->execute();

        //возвращаем объект из базы данных
        $user = $stm->fetch(\PDO::FETCH_OBJ);

        //есди пользователь не найден
        if(!$user){
            return array(
                'code' => 401,
                'user' => $user
            );
        }//if

        //проверяем пароль пользователя
        $verifyPassword = $bcrypt->verify($password, $user->password);


        //даём разрешение на авторизацию
        if($verifyPassword){
            $pattern = new patternConst();
            //проверка на подтверждение своего email
           // $status = $user->userStatusID;

            //пользователь не подтвердил свой email
            //if($status != $pattern->statusUserActive){

               // $result = array(
                    //'code' => 405,
                   // 'emailVerify' => false
                //);

               // return $result;

           // }//if



            //авторизируем пользователя
            return array(
                'code' => 200,
                'userID' => $user->userID
            );

        }//if
        else{

            //если пароли не совпадают
            return array(
                'code' => 400,
                'password' => $password
            );

        }//else

    }//LogIn
}//AuthService