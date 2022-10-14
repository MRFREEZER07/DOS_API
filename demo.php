
<h1>het therer</h1>
<?php


include 'api/lib/User.class.php';

$user =new User('karthik');
echo $user->getEmail();
$created_at =   strtotime($data['created_at']);
                    $expires_at = $created_at + $data['valid_for'];
                    $reference_token =$data['reference_token'];

                    if(time() <= $expires_at)
                    {
                        return $this->newSession(7200,$this->refresh_token);
                    }else{
                        throw new Exception("token expired");
                    }