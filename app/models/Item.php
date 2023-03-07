<?php
class Item
{
    private $db;

    //  Instantiate the Database
    public function __construct()
    {
        $this->db = new Database();
    }

    //  Get items from the database
    public function getItems()
    {
        //  We need a join because we want to get the user details for the owner
        //  Because both the users table and the items table have an id field
        //  We need to create aliases for the id fields
        $this->db->query(
            'SELECT *,
                     items.id as itemId,
                     users.id as userId,
                     items.created_at as itemCreated,
                     users.created_at as userCreated
                     FROM items
                     INNER JOIN users
                     ON items.user_id = users.id
                     ORDER BY items.created_at DESC
                     ');

        //  Retrieve multiple rows from Database
        $results = $this->db->resultSet();

        return $results;
    }

    //  Add items to the database
    public function addItem($data)
    {
        //  Prepare the statement
        $this->db->query('INSERT INTO items (item_name, user_id, item_desc) VALUES(:item_name, :user_id, :item_desc)');

        //  Bind the values
        //  The values are in the data array
        $this->db->bind(':item_name', $data['item_name']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':item_desc', $data['item_desc']);

        //  Call the execute method from the database library
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //  Show single item
    public function getItemById($id)
    {
        //  Query the database for the specific item
        $this->db->query('SELECT * FROM items WHERE id = :id');

        //  Bind to the id
        $this->db->bind(':id', $id);

        //  Get single row from the database
        $row = $this->db->single();

        //  Return the row
        return $row;
    }

    //  Update item
    public function updateItem($data)
    {
        //  Prepare the statement
        $this->db->query('UPDATE items SET item_name = :item_name, item_desc = :item_desc WHERE id = :id');

        //  Bind the values
        //  The values are in the data array
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':item_name', $data['item_name']);
        $this->db->bind(':item_desc', $data['item_desc']);

        //  Call the execute method from the database library
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //  Delete a item
    public function deleteItem($id) {
        //  Prepare the statement
        $this->db->query('DELETE FROM items WHERE id = :id');

        //  Bind the values
        //  The values are in the data array
        $this->db->bind(':id', $id);

        //  Call the execute method from the database library
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}