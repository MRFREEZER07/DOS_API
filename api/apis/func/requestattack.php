<?php
${basename(__FILE__, '.php')} = function () {
    $targetUrl = $this->_request['target'];
    $username  = $this->_request['username'];
    
    if ($this->get_request_method()=="POST" and isset($targetUrl) and isset($username) and $this->isAuthenticated()) {
            
            $s = new Functionality();
            $s->requestAttack($targetUrl,$username);
            $data = [
                "message" => "request has been made successfully"
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
