<?php
class Pages extends Controller
{
    public function __construct()
    {
        //  Load models here
    }

    //  Index method as default method
    public function index()
    {
        //  Dynamic data passed to view
        $data = [
            'title' => 'Shopping List',
            'description' => 'Please login to view your list'
        ];

        //  Return View
        $this->view('pages/index', $data);
    }
}