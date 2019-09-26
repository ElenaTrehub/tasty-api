<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 24.06.2019
 * Time: 11:45
 */

namespace Application\Services;


use Application\Utils\MySQL;

class RecipeService
{
    private function reArrayFiles($files) {
        $array = array();
        $count = count($files['name']);
        $keys = array_keys($files);

        for($i = 0; $i < $count; $i++) {
            foreach($keys as $key) {
                $array[$i][$key] = $files[$key][$i];
            }
        }

        return $array;
    }
    public function AddRecipe($recipeTitle, $calory, $recipeDescription, $ingredients, $timePrepare, $categoryID, $userID ){

        $stm = MySQL::$db->prepare("INSERT INTO recipes VALUES (DEFAULT , :recipeTitle, :calory, :recipeDescription, :ingredients, 0, 0,
 :timePrepare, :categoryID, :userID )");
        $stm->bindParam(':recipeTitle', $recipeTitle, \PDO::PARAM_STR);
        $stm->bindParam(':calory', $calory, \PDO::PARAM_INT);
        $stm->bindParam(':recipeDescription', $recipeDescription, \PDO::PARAM_STR );
        $stm->bindParam(':ingredients', $ingredients, \PDO::PARAM_STR );
        $stm->bindParam(':timePrepare', $timePrepare, \PDO::PARAM_INT);
        $stm->bindParam(':categoryID', $categoryID, \PDO::PARAM_INT);
        $stm->bindParam(':userID', $userID, \PDO::PARAM_INT);
        $stm->execute();

        $recipeID  = MySQL::$db->lastInsertId();

        if($recipeID == 0){
            $exception = new \stdClass();
            $exception->errorCode = MySQL::$db->errorCode ();
            $exception->errorInfo = MySQL::$db->errorInfo ();

            return $exception;
        }//if



        if( isset( $_FILES['images'] ) ){
           //return $_FILES['images'];
            $array = $this->reArrayFiles($_FILES['images']);

            foreach($array as $file) {
                $name =  $file['name'];

                $name = time() . "_$name";

                if( !file_exists("images")){
                    mkdir("images");
                }//if
                if( !file_exists("images/recipes")){
                    mkdir("images/recipes");
                }//if
                if( !file_exists("images/recipes/{$recipeID }")){
                    mkdir("images/recipes/{$recipeID }");
                }//if

                $path = "images/recipes/{$recipeID }/{$name}";

                if( !move_uploaded_file($file['tmp_name'] , $path) ){

                    return null;

                }//if
                $imagePath = "http://localhost:5012/tasty-api/public/{$path}";

                $stm = MySQL::$db->prepare("INSERT INTO recipephotos VALUES( DEFAULT , :recipeImagePath, :recipeID)");
                $stm->bindParam(':recipeID' , $recipeID , \PDO::PARAM_INT );
                $stm->bindParam(':recipeImagePath' , $imagePath , \PDO::PARAM_STR );
                $result = $stm->execute();

                if( $result === false ){

                    return null;

                }//if
                else{
                    continue;
                }
            }
            return true;

        }//if
    else{
        return null;
    }
    }//AddRecipe

    public function GetRecipes($offset){
        $count = 0;
        if($offset == 0){
            $count = MySQL::$db->query("SELECT COUNT(*) as count FROM recipes")->fetchColumn();
        }//if

        $limit = 8;

        $stm = MySQL::$db->prepare("SELECT recipes.recipeID, recipes.recipeTitle, recipes.userID, recipes.recipeDescription, users.userName FROM recipes INNER JOIN users USING(userID) LIMIT :offset, :limit");
        $stm->bindParam(':offset' , $offset , \PDO::PARAM_INT);
        $stm->bindParam(':limit' , $limit , \PDO::PARAM_INT);
        $stm->execute();

        $recipes = $stm->fetchAll(\PDO::FETCH_OBJ);

        if($recipes){
            $recipesList = array();
            foreach ($recipes as $recipe){

                $stm = MySQL::$db->prepare("SELECT recipephotos.recipePhotoPath FROM recipephotos WHERE recipeID = :id");
                $stm->bindParam(':id' , $recipe->recipeID , \PDO::PARAM_INT);
                $stm->execute();

                $recipePhotos = $stm->fetchAll(\PDO::FETCH_OBJ);
                if($recipePhotos){
                    $recipe->recipePhotos = $recipePhotos;
                }//if
                else{
                    $recipe->recipePhotos = "http://localhost:5012/tasty-api/public/images/recipes/no-photo.png";
                }//else

                $stm = MySQL::$db->prepare("SELECT userphotos.userPhotoPath FROM userphotos WHERE userID = :id");
                $stm->bindParam(':id' , $recipe->userID , \PDO::PARAM_INT);
                $stm->execute();

                $userPhotos = $stm->fetchAll(\PDO::FETCH_OBJ);
                if($userPhotos){
                    $recipe->userPhotos = $userPhotos;
                }//if
                else{
                    $recipe->userPhotos = "http://localhost:5012/tasty-api/public/images/recipes/no-photo.png";
                }//else

                $res = array(
                    'recipeID'=>$recipe->recipeID,
                    'recipeTitle'=>$recipe->recipeTitle,
                    'recipeDescription'=>$recipe->recipeDescription,
                    'photos'=>$recipe->recipePhotos,
                    'user'=>array(
                        'userName'=>$recipe->userName,
                        'userPhoto'=>$recipe->userPhotos,
                        )
                );
                array_push($recipesList, $res);
            }//foreach
            $response = array(

                'count'=>$count,
                'recipes'=>$recipesList
            );
            return $response;
        }//if
        else{
            return null;
        }



    }//GetRecipes

}//RecipeService