<?php
class Users extends Controller
{
    //
    public function __construct()
    {
        //
        $this->userModel = $this->model('User');
    }

    //  Register users
    //  Load the form   - Get
    //  Submit the register form - Post
    public function register()
    {
        //  Check whether it's a Get Request(load page) or a Post Request(submit form)
        //  If it's a POST request
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  Sanitise User Submitted Data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            //  Init data
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            //  Validate User Submitted Data

            //  Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } else {
                //  Check email submitted by the user
                if($this->userModel->findUserByEmail($data['email'])) {
                    //  If it is in our database we got true
                    //  If it is true, display an error message
                    $data['email_err'] = 'Email is already taken';
                }
            }

            //  First Name
            if(empty($data['first_name'])) {
                $data['first_name_err'] = 'Please enter your first name';
            }

            //  Last Name
            if(empty($data['last_name'])) {
                $data['last_name_err'] = 'Please enter your last name';
            }

            //  Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
                //  Check password length
                //  Must be 6 characters
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Your password must be at least 6 characters';
            }

            //  Confirm Password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm your password';
                //  Check if password and confirm password match
            }else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            //  Check that no error variables are filled
            //  There should be no errors before we proceed
            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                //  Once we have no errors we may proceed
                //  Validated
                //  Hash the password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                //  Register the user
                //  Call the model and register method
                if($this->userModel->register($data)) {
                    //  If true - True returned by the register method in User model

                    //  Call the Flash function
                    //  We pass the name and message but not the class, because we want to use the default success class
                    //  We need to update the Login view to show the message
                    flash('register_success', 'You are now registered and can log in');

                    //  Redirect to the login page with a success message
                    //  We could redirect using the header() function below
                    //  However, this is a good time for a helper function
                    //  header('location: ' . URLROOT . '/users/login');
                    //  Redirect using the url_redirect helper function
                    redirect('users/login');

                } else {
                    //  If false - False returned by the register method in User model
                    die('Something went wrong');
                }

            } else {
                //  After form is submitted
                //  Display the view with messages
                $this->view('users/register', $data);
            }

            //  If it's a GET request
        } else {
            //  Load the form
            //  Init data
            $data = [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            //  Load view
            $this->view('users/register', $data);
        }
    }

    //  Login users
    public function login()
    {
        //  Check whether it's a Get Request(load page) or a Post Request(submit form)
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  Sanitise User Submitted Data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            //  Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            //  Validate User Submitted Data

            //  Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            }

            //  Password
            if(empty($data['password'])) {
                //  Not validated against database - Not checking if user exists
                //  Just checking that the field has been filled out
                $data['password_err'] = 'Please enter your password';
            }

            //  Check for user/email to see if the user exists or is registered
            //  If the user is registered, we can log in the user
            if($this->userModel->findUserByEmail($data['email'])) {
                //  If the user is found, we can log in the user
                //  Then we proceed to the check below
            } else {
                //  If the user is not found, can cannot log in the user
                //  Send an error
                $data['email_err'] = 'No such user found';
            }


            //  Check that no error variables are filled
            //  There should be no errors before we proceed
            if(empty($data['email_err']) && empty($data['password_err'])) {
                //  Once we have no errors we may proceed
                //  Validated
                //  Check and set logged-in user

                //  The password here will not be the hashed password since the password here is coming from the form
                //  We will have to un-hash the password in the model
                //  This will be the user's email and password
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                //  We use the model to compare the user submitted password to the hashed password in the database
                //  If the password is correct, we return the user row, the row is all the user's data
                //  If the passwords do not match we return false
                //  In the else below we then use the false we got to take the user back to the login form with a password error
                if($loggedInUser) {
                    //  Create the session so that the user is logged in
                    //  Pass data of logged-in user to the session
                    $this->createUserSession($loggedInUser);

                }else {
                    //  If the login failed
                    //  Send the user back to the login form with an error message
                    $data['password_err'] = 'Password incorrect';

                    //  Load the view
                    $this->view('users/login', $data);
                }

            } else {
                //  After form is submitted
                //  Display the view with messages
                $this->view('users/login', $data);
            }


        } else {
            //  Load the form
            //  Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            //  Load view
            $this->view('users/login', $data);
        }
    }

    //  Put user data in session so that user is logged in
    public function createUserSession($user)
    {
        //  Put all the user's data into the session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_first_name'] = $user->first_name;
        $_SESSION['user_last_name'] = $user->last_name;

        //  Redirect to HomePage
        redirect('items');
    }

    //  Logout
    public function logout()
    {
        //  What we need to do to log out the user
        //  Is get rid of the session variables
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_first_name']);
        unset($_SESSION['user_last_name']);

        //  Destroy the session
        session_destroy();

        //  Redirect to the login page
        redirect('users/login');

    }

    //  Check if a user is logged in or not
    public function isLoggedIn()
    {
        //  Check if the user id is set in the session
        if(isset($_SESSION['user_id'])) {
            //  If the user id is set in the session then the user is logged in
            return true;
        } else {
            return false;
        }
    }

}