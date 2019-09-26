<?php
/**
 * Created by PhpStorm.
 * User: elena
 * Date: 17.06.2019
 * Time: 18:32
 */

namespace Application\Services;

use Application\Controllers\apiConstance;
use Application\Utils\MySQL;
use Closure;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\KeychainTest;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTService
{
// https://github.com/lcobucci/jwt/blob/3.3/README.md
// https://pineco.de/simple-json-web-token-guard-for-laravel-apis/
// https://blog.angular-university.io/angular-jwt-authentication/
// https://code.tutsplus.com/ru/tutorials/token-based-authentication-with-angularjs-nodejs--cms-22543
// http://phpclicks.com/php-token-based-authentication/
// https://gist.github.com/zmts/802dc9c3510d79fd40f9dc38a12bccfc
// https://git.crtweb.ru/willz/auth-bundle/blob/1.0.0/Jwt/Parser/BearerParser.php
    public function handle($headers, $userID, $ip)
    {
        $token = $this->parseTokenFromHttpRequest($headers);

        if (is_null($token)) {

           return $this->createToken($userID);

        } elseif ($token && $this->validateToken($token) === false) {
            return null;
        }
        elseif ($token && $this->validateDateToken($token) === false) {
            return $this->validateApiUser($userID, $ip);
        }
        return $token;
    }//handle

    protected function extractTokenStringFromHttpRequest($headers)
    {

        if(array_key_exists("Authorization", $headers)){
            $authHeader = $headers['Authorization'];
            $authHeaderValue = is_array($authHeader) ? end($authHeader) : $authHeader;

            return $this->extractTokenStringFromBearerValue($authHeaderValue);
        }
        return null;
    }

    /**
     * Извлекает токен из строки Bearer заголовка.
     */
    protected function extractTokenStringFromBearerValue($bearerValue)
    {
        $return = null;

        if (preg_match('/^(?:\s+)?Bearer\s(.+)$/', $bearerValue, $matches) && !empty($matches[1])) {
            $return = trim((string) $matches[1]);
        }

        return $return;
    }

    protected  function getKey(){
        $constans = new apiConstance();
        return $constans->jwt_key;
    }//getKey
    protected  function getTimeToken(){
        $constans = new apiConstance();
        return $constans->jwt_time;
    }//getKey
    protected function createToken($param2)
    {

        $signer = new Sha256();

        $builder = (new Builder)
            ->issuedAt(time())
            ->withClaim('userID', $param2)
            ->expiresAt(time() + ($this->getTimeToken()));

        $key = new Key($this->getKey());

      return (string) $builder->getToken($signer, $key);
    }//createToken

    protected function validateToken($token)
    {
        //echo gettype($token);
        $signer = new Sha256();
        $token = (new Parser)->parse($token);

        return $token->verify($signer, $this->getKey());
    }//validateToken

    protected function validateDateToken($token)
    {
        $data = new ValidationData();
        $token = (new Parser)->parse($token);

        return $token->validate($data);
    }//validateDateToken
    public function parseTokenFromHttpRequest($headers)
    {
        $tokenString = $this->extractTokenStringFromHttpRequest($headers);

        if(is_null($tokenString)){
            return null;
        }
        try {
            $token = (new Parser)->parse($tokenString);
            return $token;
        } catch (\Exception $e) {
            return $e;
        }


    }//parseTokenFromHttpRequest




    public function getCurrentUserID($headers, $ip){

        $tokenString = $this->extractTokenStringFromHttpRequest($headers);
        $token = $this->parseTokenFromHttpRequest($headers);
//echo gettype($token);
        if (is_null($token)) {

            return null;

        }
        if ($token && $this->validateToken($tokenString) === false) {
            return null;
        }
        $id = $token->getClaim('userID');
        if ($token && $this->validateDateToken($tokenString) === false) {


            $result =  $this->validateApiUser($id, $ip);

            if(is_null($result)){
                return null;
            }
            else{
                return array(
                    'userID'=>$id,
                    'token'=>$result
                );
            }//else

        }//elseif
        return array(
            'userID'=>$id,
            'token'=>null
        );;

    }//getCurrentUserID
    protected function validateApiUser($userID, $ip)
    {

        //$userID = $token->getClaim('userID');;

        $ipService = new IPService();
        $userIp = $ipService->getUserIp($userID);

        if($userIp->ip === $ip){
            return $this->createToken($userID);
        }
        return null;
    }//validateDateToken

}//JWTService