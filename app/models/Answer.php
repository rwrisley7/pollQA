<?php

use Phalcon\Mvc\Model;

/**
* Model class for Answer.  Models the answer table in the database 
* 
* @author    Robert Wrisley
*/
class Answer extends Model
{
    //answer id
    public $id;
    
    //id of question that the answer is associated to 
    public $question_id;

    //answer text
    public $text;

    //bit that indicates whether or not the answer is custom (created by the user answering a question)
    public $is_other;

    //count of how many times the answer has been used
    public $times_used;
}
