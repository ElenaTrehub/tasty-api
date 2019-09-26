<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 24.06.2019
 * Time: 11:45
 */

namespace Application\Controllers;


use Application\Services\JWTService;
use Application\Services\RecipeService;

class RecipeController extends BaseController
{
    public function addRecipeAction(){

        $recipeTitle = $this->request->GetPostValue('recipeTitle');
        //$matches = array();

        $check = preg_match('/^[а-яa-z0-9\s]{4,20}$/iu', $recipeTitle);

        if( !$check ){

            $this->json(200, array(
                'code'=>400,
                'message'=>'Некорректное название рецепта!',
                'data'=>null
            ));

            return;

        }//if
        $calory = $this->request->GetPostValue('calory');


        $check = preg_match('/^[0-9]{1,20}$/iu', $calory);

        if( !$check ){

            $this->json(200, array(
                'code'=>400,
                'message'=>'Некорректное значение калорий!',
                'data'=>null
            ));

            return;

        }//if

        $description = $this->request->GetPostValue('description');


        $check = preg_match('/^[a-zа-я0-9\s.?!&\-+:;*%@#_№\'"()\]\[]{1,1500}$/iu', $description);

        if( !$check ){

            $this->json(200, array(
                'code'=>400,
                'message'=>'Некорректное описание рецепта!',
                'data'=>null
            ));

            return;

        }//if

        $ingredients = $this->request->GetPostValue('ingredients');


       $check = preg_match('/^[a-zа-я0-9\s.?!&\-+:;*%@#_№\'"()\]\[]{1,1500}$/iu', $ingredients);

        if( !$check ){

            $this->json(200, array(
                'code'=>400,
                'message'=>'Некорректные ингредиенты!',
                'data'=>null
            ));

            return;

        }//if
        $timePrepare = $this->request->GetPostValue('timePrepare');


        $check = preg_match('/^[0-9]{1,20}$/iu', $timePrepare);

        if( !$check ){

            $this->json(200, array(
                'code'=>400,
                'message'=>'Некорректное время приготовления!',
                'data'=>null
            ));

            return;

        }//if


        $categoryID = $this->request->GetPostValue('categoryID');
        $recipeService = new RecipeService();
        $ip = $_SERVER["REMOTE_ADDR"];

        $jwtService = new JWTService();

        $result = $jwtService->getCurrentUserID($this->request->getRequestHeaders(), $ip);


        if($result){



                $add = $recipeService->AddRecipe($recipeTitle, $calory, $description, $ingredients, $timePrepare, $categoryID, $result['userID']);
                if($add){
                    if($result['token']){
                        $this->json(200, array(
                            'code'=>200,
                            'message'=>'Добавление рецепта прошло успешно!',
                            'data'=>$result['token']
                        ));
                    }
                    else{
                        $this->json(200, array(
                            'code'=>200,
                            'message'=>'Добавление рецепта прошло успешно!',
                            'data'=>null
                        ));
                        //  }

                    }

                }//if
            else{
                $this->json(200, array(
                    'code'=>500,
                    'message'=>'Dct gkj[j!!!!!',
                    'data'=>null
                ));
            }

        }//if

        else{
            $this->json(200, array(
                'code'=>400,
                'message'=>'Пользователь не авторизован!',
                'data'=>null
            ));

        }


    }//addRecipe

    public function getRecipes($offset){
        $recipeService = new RecipeService();
        $result = $recipeService->GetRecipes($offset);

        if($result){
            $this->json(200, array(
                'code'=>200,
                'message'=>'Список рецептов получен успешно!',
                'data'=>$result
            ));
        }//if
        else{
            $this->json(200, array(
                'code'=>400,
                'message'=>'Список рецептов не получен!',
                'data'=>null
            ));
        }

    }//getRecipes


}//RecipeController