<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 기본 strings_ko.xml resources
print_r($res->strings);

# manifest xml resource 
$res->setResource(_ROOT_PATH_.'/'._QUERY_.'/querys.xml', 'tables');
print_r($res->resource->tables);
echo $res->resource->tables['member'];

# manifest xml resource 
$res->setResource(_ROOT_PATH_.'/'._XML_.'/manifest.xml', 'activity');
print_r($res->resource->activity);
?>