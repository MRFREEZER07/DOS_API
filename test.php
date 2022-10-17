<?php

require "api/lib/Functionality.class.php";
require "api/lib/UserMonitor.class.php";

$f =new Functionality();
$d =new UserMonitor();
$a =$f->removeFromOnGoingAttack("karthik","subzero");
//$z=$d->attackLogForUser("karthik");
