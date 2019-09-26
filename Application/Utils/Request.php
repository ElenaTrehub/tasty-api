<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 11.06.2019
 * Time: 21:38
 */

namespace Application\Utils;


class Request
{
    public function getRequestHeaders(){
        return  getallheaders();
    }//getRequestHeaders

    public function GetGetValue($key){
        if(isset($_GET[$key])){
            return $_GET[$key];
        }//if
        else{
            return null;
        }//else
    }//GetGetValue

    public function GetPostValue($key){
        if(isset($_POST[$key])){
            return $_POST[$key];
        }//if
        else{
            return null;
        }//else
    }//GetPostValue

    public function GetPutValue( $key ){

        $params = [];

        //authorID=12&authorName=Vasya

        parse_str(
            file_get_contents("php://input") ,
            $params
        );

        if( isset($params[$key]) ){
            return $params[$key];
        }//if
        else {
            return null;
        }//else

    }//GetPutValue

}