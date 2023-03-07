<?php
/*
 *  App Core Class
 *  Creates URL & loads core controller
 *  URL FORMAT - /controller/method/params
 */

class Core
{
    //  Default controller
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        //print_r($this->getUrl());

        //  Put the requested URL in the $url variable
        $url = $this->getUrl();

        //  Check controllers for a controller that matches what was requested in the url
        //  If the param is items, then check if a items controller exists
        //  Look in controllers for first value
        //  Check if the items php file exists
        if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){

            //  If it does exist, then set it as the current controller
            //  That controller will then handle the request
            $this->currentController = ucwords($url[0]);

            //  Then unset the 0 index
            unset($url[0]);
        }

        //  Require the controller that was set as the current controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        //  Then we need to instantiate that controller class
        $this->currentController = new $this->currentController;

        //  Check for 2nd part of url
        if(isset($url[1])) {
            //  Check to see if method exists in controller
            //  Since the url is /pages/about
            //  The method would be an about method in the pages controller
            if(method_exists($this->currentController, $url[1])) {
                //  Set current method
                $this->currentMethod = $url[1];

                //  Then unset the 1 index
                unset($url[1]);
            }
        }

        //  Get params
        //  If there are parameters then add them to the url array, otherwise keep the params array an empty array
        $this->params = $url ? array_values($url) : [];

        //  Call user func array
        //  Calls a callback with an array of parameters
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

    }

    //  Gets the requested url
    public function getUrl()
    {
        //  If the url has a parameter
        if(isset($_GET['url'])) {
            //  Trim the trailing slash
            $url = rtrim($_GET['url'], '/');

            //  Sanitize as url
            $url = filter_var($url, FILTER_SANITIZE_URL);

            //  Break the URL params into an array using explode function
            $url = explode('/', $url);

            //  return the url once we've worked with it
            return $url;
        } else {
            $pages = ['Pages'];
            return $pages;
        }
    }
}