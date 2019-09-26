<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 24.06.2019
 * Time: 11:03
 */

namespace Application\Controllers;


use Application\Services\CategoryService;

class CategoryController extends BaseController
{
    public function getCategoriesListAction(){
        $categoreService = new CategoryService();

        $result = $categoreService->GetCategoriesList();

        if($result){
            $this->json(200, array(
                'code'=>200,
                'message'=>'Категории получены успешно',
                'data'=>$result
            ));
        }//if
        else{
            $this->json(200, array(
                'code'=>400,
                'message'=>'Не удалось получить категории рецептов!',
                'data'=>null
            ));
        }//else
    }//getCategoriesListAction

    public function getCategoreByIdAction($id){
$categoryID = (int)$id;
        $categoryService = new CategoryService();
        $result = $categoryService->GetCategoryByID($categoryID);
            if($result){
                $this->json(200, array(
                    'code'=>200,
                    'message'=>'Категория получа успешно',
                    'data'=>$result
                ));
            }//if
            else{
                $this->json(200, array(
                    'code'=>400,
                    'message'=>'Не удалось получить категорию!',
                    'data'=>null
                ));
            }//else
    }//getCategoreByIdAction
}