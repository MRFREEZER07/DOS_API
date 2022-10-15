<?php
${basename(__FILE__, '.php')} = function () {

    $username  = $this->_request['username'];
    
    if ($this->get_request_method()=="POST"  and isset($username) and $this->isAuthenticated()) {
            
            $s = new UserMonitor();
            $op =$s->attackLogForUser($username);
            $data = [
                "userLog" => $op
            ];
            $this->response($this->json($data), 200);
        
    }    {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
