<?php

use Phalcon\Mvc\Controller;

/**
* Controller class for Profile: allows users to view their profiles, which is currently just a list of the questions they've asked 
* and answered, and statistics about those 
* 
* @author    Robert Wrisley
*/
class ProfileController extends Controller
{
    /**
    * Default index action, simply a pass-through to the index view
    */
    public function indexAction()
    {
        $user = $this->session->get("user");
        //if user is not logged in then redirect to the login action. If they are, display a message and let it go to the index view
        if (!$user) {
            return $this->response->redirect("profile/login");
        }
    }

    /**
    * Action to log in.  Simply a pass-through
    */
    public function loginAction() 
    {
        //pass through to show login view
    }

    /**
    * Log out the user (by removing the user from session) then redirect to site index
    */
    public function logoutAction()
    {
        $this->session->remove("user");

        $this->flashSession->success("You have been logged out successfully!");
        return $this->response->redirect("index");
    }

    /**
    * Authenticate an attempted login and store user in session if successful
    */
    public function authAction()
    {
        $user = User::findFirst(
            [
                "username = :username: AND password = :password:",
                "bind" => [
                    "username" => $this->request->getPost("username"),
                    "password" => md5(trim($this->request->getPost("password")))
                ]
            ]
        );
        
        if ($user !== false) {
            //set new user in session
            $this->session->set(
                "user",
                [
                    "id" => intval($user->id),
                    "name" => $user->username
                ]
            );

            //send user to the site index
            $this->flashSession->success("Welcome, " . $user->username . "!");
            return $this->response->redirect("index");
        }

        //if failed to authenticate, alert user and go back to the login view
        $this->flashSession->error("Login failed. Please try again.");
        return $this->response->redirect("profile/login");
    }

    /**
    * Get all Questions asked by active user to show in the profile/questions view
    */
    public function questionsAction() 
    {
        $user = $this->session->get("user");

        $questions = Question::find(
            [
                "user_id = :userid:",
                "bind" => [
                    "userid" => $user['id']
                ]
            ]
        );

        //find() returns a ResultSet\Simple object, which can't be iterated over by reference, so we need to create an array to save updated questions in
        $questionsArr = [];

        //get any preset answers for all questions
        foreach ($questions as $question) {
            $answers = Answer::find(
                [
                    "question_id = :questionid:",
                    "bind" => [
                        "questionid" => $question->id
                    ]
                ]
            );
            
            //store answers in question 
            $question->answers = $answers;            
            $question->has_answers = ($answers->count() > 0) ? true : false;     

            //store question in array to persist it since $question will be overwritten and the changes to it will be lost in the next iteration of the foreach
            $questionsArr[] = $question;              
        }
           
        $this->view->questions = $questionsArr;
    }

    /**
    * Get all Answers from active user to show in the profile/answers view
    */
    public function answersAction() 
    {
        $user = $this->session->get("user");

        $query = $this->modelsManager->createQuery("
            SELECT 
                    answer.text as text,
                    answer.is_other as is_other,
                    question.text as question_text
                FROM 
                    answer 
                JOIN 
                    user_answer 
                        ON user_answer.answer_id = answer.id 
                LEFT JOIN
                    question
                        ON question.id = answer.question_id
                WHERE 
                    user_answer.user_id = :userid:
        ");
        
        $answers = $query->execute(
            [
                "userid" => $user['id']
            ]
        );   

        $this->view->answers = $answers;
    }
}
