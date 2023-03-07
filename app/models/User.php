<?php
class User
{
    private $db;

    public function __construct()
    {
        //  Initialise the Database
        $this->db = new Database();
    }

    //  Register the user
    //  The data array comes from the Users Controller
    public function register($data)
    {
        //  Prepare the statement
        $this->db->query('INSERT INTO users (first_name, last_name, email, password) VALUES(:first_name, :last_name, :email, :password)');

        //  Bind the values
        //  The values are in the data array
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        //  This will be the hashed password
        $this->db->bind(':password', $data['password']);

        //  Call the execute method from the database library
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //  User Login method
    //  Data comes from the Users Controller
    //  Data is the username and password
    //  The password is not hashed because it is the password as the user entered it in the form
    public function login($email, $password)
    {
        //  Query
        $this->db->query('SELECT * FROM users WHERE email = :email');

        //  Bind the values
        //  Find the user based on email
        $this->db->bind(':email', $email);

        //  Create a row to get all user data from the database
        $row = $this->db->single();

        //  Get the hashed password from the database
        $hashed_password = $row->password;

        //  Compared the hashed password with the user submitted password
        if(password_verify($password, $hashed_password)) {
            //  If the passwords match - The user submitted password matches the password in the database
            //  Return user data - This will be all the user data so that we can use all user data later
            return $row;
        } else {
            //  The passwords did not match - wrong password submitted by user
            return false;
        }
    }

    //  Calls methods from Database library
    //  Find user by email
    public function findUserByEmail($email)
    {
        //  Query
        //  Prepared statement with named parameter
        $this->db->query('SELECT * FROM users WHERE email = :email');

        //  Bind email named parameter to email variable
        $this->db->bind('email', $email);

        //  Get back a single result from the database
        $row = $this->db->single();

        //  We don't need to return the actual data, the email address value
        //  We just need to return true or false to see whether the value does already exist in our database
        //  If row count is greater than 0 then it means that email address was found in our database
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //  Get user by id
    public function getUserById($id)
    {
        //  Query
        //  Prepared statement with named parameter
        $this->db->query('SELECT * FROM users WHERE id = :id');

        //  Bind email named parameter to email variable
        $this->db->bind('id', $id);

        //  Get back a single result from the database
        $row = $this->db->single();

        //  Get the user data
        return $row;
    }
}