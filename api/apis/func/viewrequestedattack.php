<?php
${basename(__FILE__, '.php')} = function () {

    if ($this->get_request_method()=="POST" and $this->isAuthenticated()) {
        
            $s = new Functionality();
            $op =$s->viewRequestedAttack();
           
            $data = [
                
                "requested attacks" => $op,
            ];
            $this->response($this->json($data), 200);
        }
     else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
