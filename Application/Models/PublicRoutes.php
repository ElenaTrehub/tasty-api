<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 17.06.2019
 * Time: 14:25
 */
return array(
    'get'=>[
        '/getCurrentUser'=>'UserController@getCurrentUser',
        '/categories-list'=>'CategoryController@getCategoriesListAction',
        'get-category-by-id?(\d+)'=>'CategoryController@getCategoreByIdAction',
        '/get-recipes?(\d+)'=>'RecipeController@getRecipes'
    ],
    'post'=>[
        '/auth-user'=>'AuthoriseController@authUserAction',
        '/registryUser'=>'UserController@addUser',
        '/recipe-add'=>'RecipeController@addRecipeAction'
    ],
    'put'=>[],
    'delete'=>[]
);