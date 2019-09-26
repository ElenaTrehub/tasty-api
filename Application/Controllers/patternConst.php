<?php
/**
 * Created by PhpStorm.
 * User: YukaSan
 * Date: 19.12.2018
 * Time: 16:54
 */

namespace Application\Controllers;


class patternConst{

    public $LoginPattern = '/[a-z0-9]{4,20}$/iu';
    public $NamesPattern = '/^[a-zа-я\s\-.]{1,20}$/iu';
    public $EmailPattern = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i';
    public $PasswordPattern = '/^[a-z_?!^%()\d]{6,30}$/i';

    public $statusUserActive = 1;
    public $statusUserAnonymuse = 2;
    public $statusUserNotVerificate = 3;
    public $statusUserBlocked = 4;
    public $statusUserDelete = 5;

    public $roleUserAdmin = 1;
    public $roleUserModerator = 2;
    public $roleUserRegistred = 3;
    public $roleUserAnonymuse = 4;
}//patternConst