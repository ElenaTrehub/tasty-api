<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 19.06.2019
 * Time: 16:15
 */

namespace Application\Services;


use Application\Utils\MySQL;

class IPService
{
    public function addUserIp($userID, $ip){

        $isIp = MySQL::$db->prepare("SELECT * FROM ipes WHERE userID =:userID");
        $isIp->bindParam(':userID', $userID, \PDO::PARAM_INT );
        $isIp->execute();

        $result = $isIp->fetchAll(\PDO::FETCH_OBJ);

        if(!$result){
            $stm = MySQL::$db->prepare("INSERT INTO ipes VALUE(DEFAULT, :userID, :ip)");
            $stm->bindParam(':userID', $userID, \PDO::PARAM_INT);
            $stm->bindParam(':ip', $ip, \PDO::PARAM_STR);
            $stm->execute();

            return MySQL::$db->lastInsertId();
        }//if
        else{
            return null;
        }

    }//addUserIp
    public function getUserIp($userID){

        $stm = MySQL::$db->prepare( "SELECT * FROM ipes WHERE userID = :userID" );
        $stm->bindParam(':userID', $userID, \PDO::PARAM_STR);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);

    }//getUserIp

}//IPService