<?php

use Phalcon\Mvc\Model;

/**
* Model class for User.  Models the user table in the database 
* 
* @author    Robert Wrisley
*/
class User extends Model
{
    //user id
    public $id;

    //username
    public $username;

    //user password
    public $password;

    //NOT CURRENTLY USED.  
    //bit that determines whether the user is an admin. 
    //will allow them to delete reported questions and answers, and suspend or delete users who have had multiple questions and/or answers flagged 
    public $is_admin;
}
