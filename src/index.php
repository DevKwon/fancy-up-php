<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 세션
$auth=new AuthSession();
$auth->sessionStart();

# resouce
$res->setResource(_ROOT_PATH_.'/'._XML_.'/manifest.xml', 'activity');

# 설정 및 언어
$forms =new ReqForm($res->strings);

# 환경설정 파일 체크
if(!$_REQUEST['act']){
	Out::prints($model->langs['err_menifest_act']);
}

# ACTIVITY
$activity = $_REQUEST['act'];

# DB
//$db=new DbMySqli();

# template 선언
try{
    $tpl_dir = 'web';
    if($app->is_phone_device){ $tpl_dir = 'mobile'; }
	$tpl = new Template(_ROOT_PATH_.'/'._LAYOUT_.'/'.$tpl_dir.'/'.$res->resource->activity[$activity]);
}catch(Exception $e){
	throw new ErrorException($e->getMessage(),__LINE__);
}

# tpl 변수
$tpl['strings'] = $res->strings;

# prints
$tpl->compile_dir =_ROOT_PATH_.'/'._TPL_.'/'.$tpl_dir;
$tpl->compile = true;
$tpl->compression = false;
$tpl->display();
?>