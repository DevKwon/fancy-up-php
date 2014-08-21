<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# template 선언
try{
    $tpl = new Template(_ROOT_PATH_.'/_example/classes/template/template.html');
}catch(Exception $e){
    throw new ErrorException($e->getMessage(),__LINE__);
}

# tpl 변수
$tpl['strings'] = $res->strings;

# prints
$tpl->compile_dir =_ROOT_PATH_.'/'._TPL_;
#$tpl->compile = true;
echo $tpl->display();
?>