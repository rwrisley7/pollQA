<?php

use Phalcon\Mvc\Controller;

/**
* Controller class for Index: Simply a pass-through to the index template (point of entry)
* 
* @author    Robert Wrisley
*/
class IndexController extends Controller
{
    /**
    * Default index action, simply a pass-through to the index view
    */
    public function indexAction()
    {
        //do nothing, just let it go to the view
    }
}
