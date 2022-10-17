<?php

require_once "Database.class.php";

class Functionality 
{
public function __construct()
{
    $this->db =Database::get_connection();
    $this->onGoingData =""; //holds total data which is going to converted in to json
}




public function requestAttack($target,$username)
{
    $query = "INSERT INTO `attackRequests` (`username`, `target`) VALUES ('$username', '$target');";
    $result =mysqli_query($this->db, $query);
    if($result){
        return true;
        print("successfuully inserted");
    }else{
        return false;
        print("error in insertion");
    }
}



public function viewOnGoingAttacks()
{
   
    $query ="SELECT * FROM `ongoingAttack`;";
    $result =mysqli_query($this->db, $query);
    
    
        if ($result->num_rows > 0) {
            // output data of each row
            $data =[];
                while($row = $result->fetch_assoc()) {
                    array_push($data,$row);
            }
            //print_r($data);
        return $data;
                  
    } else {
        return false;
        throw new Exception("NoDataFound");
    }
}
public function viewRequestedAttack()
{
 $query ="SELECT * FROM `attackRequests`;";
 $result =mysqli_query($this->db, $query);
 if($result->num_rows>0)
 {
    $data =[];
    while($row = $result->fetch_assoc())
    {
        array_push($data,$row);
    }
    return $data;
 }else
    {
    return false;
    throw new Exception("NoDataFound");
     }

}

public function deleteAttackRequest($username)
{
    $query ="DELETE FROM `attackRequests` WHERE ((`username` = '$username'));";
    $result =mysqli_query($this->db, $query);
    
    if(mysqli_affected_rows($this->db))
    {
        return true;
    }else{
        return false;
    }
}

public function AllAttacks()
{
    
    $onGoingAtack = $this->viewOnGoingAttacks();
    $requestedAttack =$this->viewRequestedAttack();
    print_r($onGoingAtack);
}

public function joinAnAttack($attackLauncher,$userToJoin)
{   
    
    $query = "SELECT attackers FROM `ongoingAttack` WHERE attack_launcher='$attackLauncher' ;";
    $result =mysqli_query($this->db, $query);
    
    if($result->num_rows > 0)
    {
        $op =mysqli_fetch_assoc($result);
        $exist =false;
        $existing =$op["attackers"];
        $new = explode(",", $existing);
       
        for($i=0;$i<sizeof($new);$i++)
        {
            if($new[$i] == $userToJoin)
            {  
                $exist =true;
                
            }
        }
        
        if(!$exist){
            $newList = $existing . "," .$userToJoin;
            $queryToAdd ="UPDATE ongoingAttack SET attackers = '$newList' WHERE attack_launcher = '$attackLauncher';";
            $newRes =mysqli_query($this->db, $queryToAdd);
        if($newRes){
            return true;
        }else
        {
            throw new Exception("cannot updated");
            return false;
        }
        }
        
    }
    else{
        throw new Exception("no attack exist");
        return false;
    }

}

public function removeFromOnGoingAttack($attackLauncher,$userToLeave)
{

    $query = "SELECT attackers FROM `ongoingAttack` WHERE attack_launcher='$attackLauncher' ;";
    $result =mysqli_query($this->db, $query);
    if($result->num_rows > 0)
    {
        
        $op =mysqli_fetch_assoc($result);
        
        $existing =$op["attackers"];
        
        $new = explode(",", $existing);
        print(sizeof($new));
        $finalList="";
        for($i=0;$i<sizeof($new);$i++)
        {
               // print($new[$i]."\n");
               if($userToLeave != $new[$i])
               {
                $finalList = $finalList .",". $new[$i];
               }
        }
        print($finalList);
       
       
    
        $queryToAdd ="UPDATE ongoingAttack SET attackers = '$finalList' WHERE attack_launcher = '$attackLauncher';";
        $result =mysqli_query($this->db, $queryToAdd);
        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }

    }
    
}

}







