<?php
//  Sessions
session_start();

//  Flash message helper
//  Name, Message, Optional Class - Shows green success by default. Otherwise, pass in alert alert-danger to override to red error message
//  Example - flash('register_success', 'You are now registered');
//  Display in View - <?php echo flash('register_success'); ?\>
function flash($name = '', $message = '', $class = 'alert alert-success')
{
    //  Check if there is a name passed in
    if(!empty($name)) {
        //  If there is a message, and it's not set in the session
        //  If the message is not empty and the session name is empty
        if(!empty($message) && empty($$_SESSION[$name])) {

            //  Check if they are there
            //  If they are there then unset them before we reset them below
            if(!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if(!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }


            //  Set the session message
            $_SESSION[$name] = $message;
            //  Set the class name
            $_SESSION[$name . '_class'] = $class;

            //  Check if there is no message but it was set in the session
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

//  Protected Routes
//  In order for a user to see items, they need to be logged in
//  Otherwise they will be redirected to the login page
//  Check if a user is logged in or not
function isLoggedIn()
{
    //  Check if the user id is set in the session
    if(isset($_SESSION['user_id'])) {
        //  If the user id is set in the session then the user is logged in
        return true;
    } else {
        return false;
    }
}





