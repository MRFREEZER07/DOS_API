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
        print("successfuully inserted");
    }else{
        print("error in insertion");
    }
}

public function joinAnAttack($attackLauncher,$userToJoin)
{   
    
    $query = "SELECT attackers FROM `ongoingAttack` WHERE attack_launcher='$attackLauncher' ;";
    $result =mysqli_query($this->db, $query);
    if($result)
    {
        $op =mysqli_fetch_assoc($result);
        $existing =$op["attackers"];
        $newList = $existing . "," .$userToJoin;
        $queryToAdd ="UPDATE ongoingAttack SET attackers = '$newList' WHERE attack_launcher = '$attackLauncher';";
        $newRes =mysqli_query($this->db, $queryToAdd);
        if($newRes){
            print("updated successfully");
        }else
        {
            throw new Exception("cannot updated");
        }
    }
    else{
        throw new Exception("no attack exist");
    }

}

public function viewOnGoingAttacks()
{
    $query ="SELECT * FROM `ongoingAttack`;";
    $result =mysqli_query($this->db, $query);

    if ($result) {
        $op =mysqli_fetch_all($result,MYSQLI_ASSOC);
        foreach($op as $datum)
            {
                return (array(
                    "target"=>$datum['target'],
                    "attackers"=>$datum['attackers'],
                    "attack_launcher"=>$datum['attacker_launcher']
                ));
            }
    } else {
        throw new Exception("NoDataFound");
    }

}





}

