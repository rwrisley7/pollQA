<?php

use Phalcon\Mvc\Model;

/**
* Model class for Question.  Models the question table in the database 
* 
* @author    Robert Wrisley
*/
class Question extends Model
{
    //question id
    public $id;

    //id of user who asked the question
    public $user_id;

    //question text
    public $text;

    //bit that determines whether or not a user can create their own answer when answering the question
    public $allow_other;
}
