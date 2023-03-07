<?php

//  Items Controller
class Items extends Controller
{
    //  Constructor
    public function __construct()
    {
        //  Check if session user id is there
        //  If it is there, it means the user is logged in
        //  If it's not there the user is not logged in and cannot see items
        //  Putting this in the constructor will make sure that for all items all users will need to be logged in
        if(!isLoggedIn()) {
            //  If not logged in, redirect to login page
            redirect('users/login');
        }

        //  Load items model
        $this->itemModel = $this->model('Item');

        //  Load user model
        $this->userModel = $this->model('User');
    }

    //  Index Method to load items index page
    public function index()
    {
        //  Get items
        $items = $this->itemModel->getItems();

        //  Data array holds items
        $data = [
            'items' => $items
        ];

        //  Return a view
        $this->view('items/index', $data);
    }

    //  Add Method to add an item
    public function add()
    {
        //  Check whether an item request was received
        //  If it was a post request, process it as adding a new item
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  Sanitise the item array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            //  Data Submitted
            $data = [
                'item_name' => trim($_POST['item_name']),
                'item_desc' => trim($_POST['item_desc']),
                'user_id' => $_SESSION['user_id'],
                'item_name_err' => '',
                'item_desc_err' => ''
            ];

            //  Validate submitted data
            //  Validate Title
            if(empty($data['item_name'])) {
                $data['item_name_err'] = 'Please enter a name for your item';
            }

            //  Validate Body
            if(empty($data['item_desc'])) {
                $data['item_desc_err'] = 'Please enter a description';
            }

            //  Make sure there are no errors
            if(empty($data['item_name_err']) && empty($data['item_desc_err'])) {
                //  Validated
                if($this->itemModel->addItem($data)) {
                    //  Item model method - add item
                    flash('item_message', 'Item added successfully');
                    redirect('items');
                } else {
                    //
                    die('Something went wrong');
                }

            } else {
                //  There are no errors and we can proceed
                //  Load the view with errors
                $this->view('items/add', $data);
            }

        } else {
            //  It was a get request
            //  Load the add new item form
            //  Data array holds items
            $data = [
                'item_name' => '',
                'item_desc' => ''
            ];
        }

        //  Load the view
        $this->view('items/add', $data);
    }

    //  Show single item
    public function show($id)
    {
        //
        $item = $this->itemModel->getItemById($id);
        $user = $this->userModel->getUserById($item->user_id);

        //  Item variable that hold row from database goes into the data array
        $data = [
            'item' => $item,
            'user' => $user
        ];

        $this->view('items/show', $data);
    }

    //  Edit Method to edit a item
    public function edit($id)
    {
        //  Check whether a post request was received
        //  If it was a post request, process it as adding a new item
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  Sanitise the item array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            //  Data Submitted
            $data = [
                'id' => $id,
                'item_name' => trim($_POST['item_name']),
                'item_desc' => trim($_POST['item_desc']),
                'user_id' => $_SESSION['user_id'],
                'item_name_err' => '',
                'item_desc_err' => ''
            ];

            //  Validate submitted data
            //  Validate Title
            if(empty($data['item_name'])) {
                $data['item_name_err'] = 'Please enter a name';
            }

            //  Validate Body
            if(empty($data['item_desc'])) {
                $data['item_desc_err'] = 'Please enter a description';
            }

            //  Make sure there are no errors
            if(empty($data['item_name_err']) && empty($data['item_desc_err'])) {
                //  Validated
                if($this->itemModel->updateItem($data)) {
                    //  Item model method - add item
                    flash('item_message', 'Item updated successfully');
                    redirect('items');
                } else {
                    //
                    die('Something went wrong');
                }

            } else {
                //  There are no errors, and we can proceed
                //  Load the view with errors
                $this->view('items/edit', $data);
            }

        } else {

            //  Fetch existing item from model
            $item = $this->itemModel->getItemById($id);

            //  Check for item owner
            //  If this is not the owner of the item
            if($item->user_id != $_SESSION['user_id']) {
                //  Do not allow them to edit
                //  Redirect to Items
                redirect('items');
            }

            //  It was a get request
            //  Load the add new item form
            //  Data array holds items
            $data = [
                'id' => $id,
                'item_name' => $item->item_name,
                'item_desc' => $item->item_desc
            ];
        }

        //  Check who the owner of the item is
        //  Items should only be editable to its owner
        //  Load the view
        $this->view('items/edit', $data);
    }

    //  Delete an item
    public function delete($id)
    {
        //  This should always be a post request
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  Fetch existing item from model
            $item = $this->itemModel->getItemById($id);

            //  Check for item owner
            //  If this is not the owner of the item
            if($item->user_id != $_SESSION['user_id']) {
                //  Do not allow them to edit
                //  Redirect to Items
                redirect('items');
            }


            //  If it is a post request then we can handle the request
            if($this->itemModel->deleteItem($id)) {
                //  Flash a message that the item was deleted
                flash('item_message', 'Item Removed');

                //  Redirect to items
                redirect('items');
            } else {
                //  If the request failed
                die('Something went wrong');
            }
        } else {
            //  If it's a get request then redirect to items
            redirect('items');
        }
    }
}