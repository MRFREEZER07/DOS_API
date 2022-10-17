<?php
${basename(__FILE__, '.php')} = function () {

    if ($this->get_request_method()=="POST" and $this->isAuthenticated()) {
            $username  = $this->_request['username'];
            $s = new Functionality();
            $op =$s->deleteAttackRequest($username);
            if($op){
                $data = [
                
                    "Mesage" => "Successfullly deleted",
                ];
                $this->response($this->json($data), 200);
            }else{
                $data = [
                
                    "Mesage" => "There is no attack request",
                ];
                $this->response($this->json($data), 200);
            }
           
           
        }
     else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
