<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 11.06.2019
 * Time: 21:51
 */

namespace Application\Services;


use Application\Utils\MySQL;
use Bcrypt\Bcrypt;

class UserService
{
    public function addUser($userLogin, $usrPassword, $userEmail, $userName,  $heshToken, $statusUser, $roleUser){

        $isUser = MySQL::$db->prepare("SELECT * FROM users WHERE userLogin = :userLogin");
        $isUser->bindParam(':userLogin', $userLogin,\PDO::PARAM_STR);
        $isUser->execute();

        $result = $isUser->fetchAll(\PDO::FETCH_OBJ);

        if(!$result){

            $bcrypt = new Bcrypt();
            $bcrypt_version = '2y';
            $heshPassword = $bcrypt->encrypt($usrPassword,$bcrypt_version);

            $stm = MySQL::$db->prepare("INSERT INTO users VALUES( DEFAULT, :userLogin, :password, :userEmail , :userName ,  :userStatusID , :userRoleID , :heshToken )");
            $stm->bindParam(':userLogin' , $userLogin , \PDO::PARAM_STR);
            $stm->bindParam(':password' , $heshPassword , \PDO::PARAM_STR);
            $stm->bindParam(':userEmail' , $userEmail , \PDO::PARAM_STR);
            $stm->bindParam(':userName', $userName, \PDO::PARAM_STR);
            $stm->bindParam(':userStatusID', $statusUser, \PDO::PARAM_INT);
            $stm->bindParam(':userRoleID', $roleUser, \PDO::PARAM_INT);
            $stm->bindParam(':heshToken' , $heshToken , \PDO::PARAM_STR);

            $stm->execute();

            return  MySQL::$db->lastInsertId();

        }//if

        return null;
    }//addUser

    public function verificationUser($token, $statusUser){

        $stm = MySQL::$db->prepare("UPDATE users SET userStatusID = :userStatusID, token = NULL WHERE token =:token");
        $stm->bindParam('token', $token,\PDO::PARAM_STR);
        $stm->bindParam(':userStatusID', $statusUser, \PDO::PARAM_INT);
        $stm->execute();

        return  $stm->fetch(\PDO::FETCH_OBJ);

    }

    public function getSingleUser($identifier){

        $stm = MySQL::$db->prepare("SELECT userName FROM users WHERE userLogin = :userLogin OR userID=:userID ");

        $stm->bindParam(':userLogin', $identifier,\PDO::PARAM_STR);
        $stm->bindParam(':userID',$identifier ,\PDO::PARAM_STR);

        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);

    }//getSingleUser
}//UserService