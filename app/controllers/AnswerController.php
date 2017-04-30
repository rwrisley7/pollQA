<?php

use Phalcon\Mvc\Controller;

/**
* Controller class for Answer: allows users to answer questions
* 
* @author    Robert Wrisley
*/
class AnswerController extends Controller
{
    /**
    * Default index action, but there isn't much going on on this page so we can put everything in the index
    */
    public function indexAction()
    {
        //get logged-in user (if exists)
        $user = $this->session->get("user");

        //if the user is logged-in, get a random question that doesn't belong to them and that they haven't answered.  Otherwise, get any random question
        if (!empty($user) && array_key_exists('id', $user) && !empty($user['id'])) {
            $question = $this->getNonUserQuestion($user['id']);
        } else {
            $question = $this->getAnyQuestion();
        }

        //get any preset answers for the chosen question
        $answers = Answer::find(
            [
                "question_id = :questionid: AND (is_other IS NULL OR is_other != 1)",
                "bind" => [
                    "questionid" => $question->id
                ]
            ]
        );

        //store answers in question since they technically belong to it 
        $question->answers = $answers;

        //assign question to the view
        $this->view->question = $question;
    }

    /**
    * Process the user's answer submission and save it if it's valid
    */
    public function answerAction()
    {
        $questionId = $this->request->getPost("question_id");
        $answerId = $this->request->getPost("answer");
        $other = $this->request->getPost("other");

        //check if user selected an answer and redisplay question form if they did not
        if (is_null($answerId) || ($answerId === "0" && empty($other))) {      
            $this->flashSession->error("Please select an answer!");

            //fetch same question and answers to redisplay so user can try to save again
            return $this->showQuestion($questionId);

        }

        //create answer if it is custom
        if ($answerId === "0") {
            $answer = new Answer();

            $answer->question_id = $questionId;
            $answer->text = $other;
            $answer->is_other = 1;
            $answer->times_used = 1;

            $success = $answer->save();
        } else {
            //if answer is a predefined option then just update times_used count            
            $answer = Answer::findFirst(
                [
                    "id = $answerId"
                ]
            );

            $answer->times_used++;

            $success = $answer->save();
        }
        

        if ($success) {
            //create a user_answer record if the user is logged-in
            $user = $this->session->get("user");

            if (!empty($user) && array_key_exists('id', $user) && !empty($user['id'])) {
                $userAnswer = new User_answer();

                $userAnswer->user_id = $user['id'];
                $userAnswer->answer_id = $answer->id;

                $success = $userAnswer->save();
            }        
        }

        //if everything saved successfully, redirect to index action with a flash message 
        if ($success) {
            $this->flashSession->success("Answer saved!");

            //go back to the Answer index (the answer question form)
            return $this->response->redirect("answer");
        } else {
            $this->flashSession->error("Answer NOT saved: " . implode(", ", $answer->getMessages()));

            //fetch same question and answers to redisplay so user can try to save again
            return $this->showQuestion($questionId);
        }
    }

    /**
    * Get a random question that does not belong to the logged-in user and that they have not previously answered
    */
    private function getNonUserQuestion($userId)
    {
        //phalcon does not seem to support subqueries, so we gotta break it up and do it in 2
        
        //get all ids for questions that the user has already answered
        $subquery = $this->modelsManager->createQuery("
            SELECT answer.question_id 
                FROM answer 
                LEFT JOIN user_answer ON user_answer.answer_id = answer.id 
                WHERE user_answer.user_id = :userid:
        ");

        $subResultSet = $subquery->execute(
            [
                "userid" => $userId
            ]
        );

        //get all answered question ids into an array for a future step
        $userQuestions = [];
        foreach ($subResultSet as $subResult) {
            $userQuestions[] = $subResult->question_id;
        }

        //get al questions that do not belong to the user
        $resultSet = Question::find(
            [
                "user_id != :userid: OR user_id IS NULL AND id NOT IN (:userquestions:)",
                "bind" => [
                    "userid" => $userId,
                    "userquestions" => implode(", ", $userQuestions)
                ]
            ]
        );

        //get all valid questions (don't belong to the user and ones that the user hasn't answered)
        $questions = [];
        foreach ($resultSet as $question) {
            if (!in_array($question->id, $userQuestions)) {
                $questions[] = $question;
            }
        }
        
        //choose a random valid question to return
        $offset = mt_rand(0, count($questions)-1);
            
        return $questions[$offset];
    }

    /**
    * Get a random question
    */
    private function getAnyQuestion()
    {
        $count = Question::count();
        $offset = mt_rand(0, $count-1);

        $questions = Question::find(
            [
                "limit" => ["offset" => $offset, "number" => 1] 
            ]
        );
        
        //find() will only return 1 record because of the limit, but it returns it in a ResultSet so we need to extract the result
        return $questions->getFirst();
    }

    //fetch the question with the provided id and show index view
    private function showQuestion($questionId)
    {       
        $question = Question::findFirst(
            [
                "id = :questionid:",
                "bind" => [
                    "questionid" => $questionId
                ]
            ]
        );
                
        //get any preset answers for the chosen question
        $answers = Answer::find(
            [
                "question_id = :questionid: AND (is_other IS NULL OR is_other != 1)",
                "bind" => [
                    "questionid" => $question->id
                ]
            ]
        );

        //store answers in question since they technically belong to it 
        $question->answers = $answers;

        //assign question to the view
        $this->view->question = $question;

        //go directly to index view
        return $this->view->pick(
            [
                "answer/index"
            ]
        );
    }
}
