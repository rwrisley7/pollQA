<?php

use Phalcon\Mvc\Controller;

/**
* Controller class for Register: allows users to register for accounts
* 
* @author    Robert Wrisley
*/
class RegisterController extends Controller
{
    /**
    * Default index action, simply a pass-through to the index view
    */
    public function indexAction()
    {
        //do nothing, just let it go to the view
    }

    /**
    * Action to create a new user record, and if successful, log them in by storing user in session
    */
    public function registerAction()
    {
        //Validation before attempting to create new user
        if (trim($this->request->getPost("password")) !== trim($this->request->getPost("password2"))) {
            $this->flashSession->error("Failed to register: Passwords did not match");
        } else if (empty(trim($this->request->getPost("username")))) {
            $this->flashSession->error("Failed to register: username is required");
        } else if (empty(trim($this->request->getPost("password")))) {
            $this->flashSession->error("Failed to register: password is required");
        } else {
            //attempt to create new user
            $user = new User();
            $user->username = $this->request->getPost("username");
            $user->password = md5(trim($this->request->getPost("password")));

            $success = $user->save();

            if ($success) {
                $this->flashSession->success("Registered successfully!");

                //set new user in session
                $this->session->set(
                    "user",
                    [
                        "id" => $user->id,
                        "name" => $user->username
                    ]
                );
        
                //go to the site index
                return $this->response->redirect("index");
            } else {
                $this->flashSession->error("Failed to register: " . implode(", ", $user->getMessages()));
            }
        }
        
        //go back to the Register index since registration was unsuccessful
        return $this->response->redirect("register");
    }
}
