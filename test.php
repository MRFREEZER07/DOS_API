<?php

require "api/lib/Functionality.class.php";
require "api/lib/UserMonitor.class.php";

$f =new Functionality();
$d =new UserMonitor();
//$a =$f->joinAnAttack("karthik","newuser");
$z=$d->attackLogForUser("karthik");
print_r($z);