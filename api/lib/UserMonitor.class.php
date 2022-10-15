<?php

class UserMonitor
{

    public function __construct()
    {
        $this->db =Database::get_connection();
    }
    
    public function attackLogForUser($username)
    {
       
        $query ="SELECT * FROM attacksLog WHERE username ='$username' ";
        $result =mysqli_query($this->db, $query);
        if ($result->num_rows > 0) {
            // output data of each row
            $data =[];
                while($row = $result->fetch_assoc()) {
                    array_push($data,$row);
            }
            //print_r($data);
        return $data;
                  
    } 
        else{
            print("error in insertion");
        }
    }

    public function viewPreviousAttacks()
    {

    }
}