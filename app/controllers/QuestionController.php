<?php

use Phalcon\Mvc\Controller;

/**
* Controller class for Question: allows users to ask new questions
* 
* @author    Robert Wrisley
*/
class QuestionController extends Controller
{
    /**
    * Default index action, simply a pass-through to the index view
    */
    public function indexAction()
    {
        //do nothing, just let it go to the view
    }

    public function askAction()
    {
        //if the user has not entered any answers or allowed for custom answers, then the Question can never be answered and is therefore invalid 
        if (!$this->hasAnswers()) {
            $this->flashSession->error("Invalid Question, must provide answers to choose from and/or allow custom answers");
            //go back to the Questions index (the enter question form)
            return $this->response->redirect("question");
        } 
        
        $question = new Question();

        //format the question text
        $text = ucfirst(trim($this->request->getPost("text")));
        if (count($text) > 0 && substr($text, -1) != '?') {
            $text = preg_replace('/[^a-zA-Z0-9]$/', '', $text) . '?';
        }
        $question->text = $text;

        $question->allow_other = $this->request->getPost("allow_other");
        
        $user = $this->session->get("user");
        if (!empty($user) && array_key_exists('id', $user) && !empty($user['id'])) {
             $question->user_id = $user['id'];
        }
        //attempt to save new Question
        $success = $question->save();

        //if failed, dump error message 
        if (!$success) {
            $this->flashSession->error("Question NOT saved: " . implode(", ", $question->getMessages()));
        } else {
            //if question was saved successfully and answers were provided, save any provided answers
            $answers = $this->request->getPost('answers');
            foreach ($answers as $answer) {
                //ignore empty answer fields
                if (!empty($answer)) {
                    $answerModel = new Answer(
                        [
                            'question_id' => $question->id,
                            'text' => $answer
                        ]
                    );

                    $success = $answerModel->save();

                    if (!$success) {
                        //if any answers failed, echo out error messages then kick out of loop and go to fail message.  
                        //Data cleanup will be added at a later date   
                        $this->flashSession->error("Question (answer) NOT saved: " . implode(", ", $question->getMessages()));

                        break;
                    }
                }
            }
        }

        if ($success) {
            $this->flashSession->success("Question saved!");
        } 
        
        //go back to the Questions index (the enter question form)
        return $this->response->redirect("question");
    }

    /**
    * Check that either the user defined answer presets or is allowing for custom answers and return the result
    *
    * @return boolean
    */
    private function hasAnswers()
    {       
        if (intval($this->request->getPost('allow_other')) === 1) {
            return true;
        }

        $answers = $this->request->getPost('answers');
        foreach ($answers as $answer) {
            if (!empty($answer)) {
                return true;
            }
        }

        return false; 
    } 
}
