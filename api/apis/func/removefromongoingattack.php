<?php
${basename(__FILE__, '.php')} = function () {
    $attackLauncher = $this->_request['attackerlauncher'];
    $userToLeave  = $this->_request['usertoleave'];
    if ($this->get_request_method()=="POST" and $this->isAuthenticated() and isset($attackLauncher) and isset($userToLeave)) {
        try {
            
            $s = new Functionality();
            $s->removeFromOnGoingAttack($attackLauncher,$userToLeave);
            $data = [
                "message" => "Successfully joinded the attack"
            ];
            $this->response($this->json($data), 200);
        } catch (Exception $e) {
            $data = [
                "error" => $e->getMessage()
            ];
            $this->response($this->json($data), 409);
        }
    } else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
