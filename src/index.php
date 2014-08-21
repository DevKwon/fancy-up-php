<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 세션
$auth=new AuthSession();
$auth->sessionStart();

# 함수
include_once $path.'/function/fun_url_parse.php';

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
	$tpl = new Template(_ROOT_PATH_.'/'._LAYOUT_.'/'.$res->resource->activity[$activity]);
}catch(Exception $e){
	throw new ErrorException($e->getMessage(),__LINE__);
}

# url querys
$_params = url_query_parse_array($_SERVER['QUERY_STRING']);
$_params['state'] = time();
$_params['act'] = $_REQUEST['act'];

# tpl 변수
$tpl['params_json'] = str_replace('"', "'",json_encode($_params));
$tpl['params'] = $_params;
$tpl['strings'] = $res->strings;

# prints
$tpl->compile_dir =_ROOT_PATH_.'/'._TPL_;
#$tpl->compile = true;
echo $tpl->display();
?>