<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 24.06.2019
 * Time: 11:05
 */

namespace Application\Services;


use Application\Utils\MySQL;

class CategoryService
{
    public function GetCategoriesList(){
        $stm = MySQL::$db->prepare("SELECT * FROM categories");
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_OBJ);

    }//GetCategoriesList

    public function GetCategoryByID($id){
        $stm = MySQL::$db->prepare("SELECT * FROM categories WHERE categoryID=:categoryID");
        $stm->bindParam(':categoryID', $id, \PDO::PARAM_INT );
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);
    }//GetCategoryByID
}