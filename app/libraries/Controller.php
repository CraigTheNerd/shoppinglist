<?php
/*
 * This is the base controller
 * Every controller will extend this controller
 * This loads the models and views
 */

class Controller
{
    //  Load model
    public function model($model)
    {
        //  Require model file
        require_once '../app/models/' . $model . '.php';

        //  Instantiate the model
        return new $model;
    }

    // Load view
    //  The data array passes data to the view
    //  This data might be hard coded data, user submitted data or data from the database
    public function view($view, $data = [])
    {
        //  Check for the view file
        if(file_exists('../app/views/' . $view . '.php'))
        {
            //
            require_once '../app/views/' . $view . '.php';
        } else {
            //  View does not exist
            die('View does not exist');
        }
    }
}