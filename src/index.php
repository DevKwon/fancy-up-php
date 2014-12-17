<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

#IE<=8 접속 경고 및 차단
if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])){
    Out::window_location('https://www.google.com/intl/ko/chrome/browser/', $res->strings['warning_browser']);
}

# 변수
$menu = array();
$sub_menu =array();

# 세션
$auth=new AuthSession();
$auth->sessionStart();

# resouce
$res->setResource(_ROOT_PATH_.'/'._RES_.'/manifest.xml', 'activity');

# 설정 및 언어
$forms =new ReqForm($res->strings);

# 환경설정 파일 체크
if(!$_REQUEST['act']){
	Out::prints($model->langs['err_manifest_not_registered']);
}

# ACTIVITY
$activity = $_REQUEST['act'];

# template 선언
try{
    $tpl_dir = 'web';
    //if($app->is_phone_device){ $tpl_dir = 'mobile'; }
	$tpl = new Template(_ROOT_PATH_.'/'._LAYOUT_.'/'.$tpl_dir.'/'.$res->resource->activity[$activity]);
}catch(Exception $e){
	throw new ErrorException($e->getMessage(),__LINE__);
}

# 메뉴
$res->setResource(_ROOT_PATH_.'/'._MENU_.'/menu.xml', 'web');
$menu = $res->resource->web['menu'];

# 서브메뉴
if($_GET['mid'] !='')
{
    $sub_key = 'sub_'.substr($_GET['mid'],0,2).'00';
    $res->setResource(_ROOT_PATH_.'/'._MENU_.'/menu.xml', $sub_key);
    $sub_menu = $res->resource->{$sub_key}['menu'];
    if(!isset($sub_menu[0])){
        $sub_menu=array($sub_menu);
    }
}

# tpl 변수
$tpl['strings']      = $res->strings;
$tpl['navi']         =$menu;
$tpl['navi_sub']     =$sub_menu;
$tpl['http_referer'] =(!is_null($app->http_referer))? $app->http_referer : _SITE_HOST_;
$tpl['is_apple_device']=$app->is_apple_device();

# prints
$tpl->compile_dir =_ROOT_PATH_.'/'._TPL_.'/'.$tpl_dir;
$tpl->compile     = true;
$tpl->compression = false;
$tpl->display();
?>