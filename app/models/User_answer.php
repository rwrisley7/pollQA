<?php

use Phalcon\Mvc\Model;

/**
* Model class for User Answer association.  Models the user_answer table in the database 
* 
* @author    Robert Wrisley
*/
class User_answer extends Model
{
    //user_answer id
    public $id;
    
    //id of user who selected the answer
    public $user_id;

    //id of answer selected by user
    public $answer_id;
}
