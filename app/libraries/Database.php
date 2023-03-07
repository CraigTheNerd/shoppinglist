<?php
/*
 * PDO Database Class
 * Connect to Database
 * Create prepared statements
 * Bind values
 * Return rows and results
 */

class Database
{
    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;

    //  Database Handler
    //  Whenever we prepare a statement we're going to use this database handler
    private $dbh;

    //  Statement
    private $stmt;

    //  Errors
    private $error;

    public function __construct()
    {
        //  DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        //  Options
        $options = array(
            //  Improves performance by checking whether there is already an established connection to the database
            PDO::ATTR_PERSISTENT => true,

            //  Error mode handling - Exceptions
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        //  Create PDO Instance
        try {
            //  Try to create the new PDO instance, otherwise catch the error
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            //  Handle the Exception
            //  Gets the error message
            $this->error = $e->getMessage();

            //  echo the error message
            echo $this->error;
        }

    }

    //  Queries
    //  Prepare statement with query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    //  Bind values
    public function bind($param, $value, $type = null)
    {
        //  Check for param datatype otherwise default to string
        if(is_null($type)) {
            //
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        //  Bind Values
        $this->stmt->bindValue($param, $value, $type);

    }

    //  Execute the prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    //  Result Set - Get results from database as array of objects
    public function resultSet() {
        //  Call the execute
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //  Single Result - Get single result from database
    public function single()
    {
        //  Get a single row
        //  Call the execute
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    //  Get row count
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

}