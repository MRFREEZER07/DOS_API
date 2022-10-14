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
        if($result){
            $op =mysqli_fetch_assoc($result);
         
            return array(
                "username"=>$op['username'],
                "target"=>$op['target'],
                "totalTime"=>$op['total_time'],
                "attackers"=>$op['attackers']
            );
        }else{
            print("error in insertion");
        }
    }

    public function viewPreviousAttacks()
    {

    }
}