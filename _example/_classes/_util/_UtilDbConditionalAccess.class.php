<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# manifest xml resource 
$wh = array("name!=''", "email='ddd@dddd.com'", "passwd !=''");
$udca = new UtilDbConditionalAccess(implode(" AND ", $wh));
$udca->setAND('age', 20);
echo $where = $udca->where;
?>