<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

#IE<=8 접속 경고 및 차단
if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])){
    Out::window_location('https://www.google.com/intl/ko/chrome/browser/', $res->strings['warning_browser']);
}

# 세션
$auth=new AuthSession('admin');
$auth->sessionStart();

# 변수
$menu = array();
$sub_menu = array();

# resouce
$res->setResource(_ROOT_PATH_.'/'._RES_.'/manifest_admin.xml', 'activity');

# 설정 및 언어
$forms =new ReqForm($res->strings);

# 환경설정 파일 체크
$activity = 'index';
if(!$_REQUEST['act']){
    if(!$auth->uid)
        $activity = 'login';
}else{
    $activity = $_REQUEST['act'];
}

# template 선언
try{
    $tpl = new Template(_ROOT_PATH_.'/'._LAYOUT_.'/admin/'.$res->resource->activity[$activity]);
}catch(Exception $e){
    throw new ErrorException($e->getMessage(),__LINE__);
}

if($activity!='login')
{
    # 메뉴
    $res->setResource(_ROOT_PATH_.'/'._MENU_.'/menu_admin.xml', 'admin');
    $menu = $res->resource->admin['menu'];

    # 서브메뉴
    if($_GET['mid'] !='') {
        $sub_key = 'admin_'.substr($_GET['mid'],0,2).'00';

        $res->setResource(_ROOT_PATH_.'/'._MENU_.'/menu_admin.xml', $sub_key);
        $sub_menu = $res->resource->{$sub_key}['menu'];
        if(!isset($sub_menu[0])){
            $sub_menu=array($sub_menu);
        }
    }

    # 메뉴권한
    $menuAuthStorage = new AdminMenuAuthStorage(_ROOT_PATH_.'/'._UPLOAD_.'/admin/menuauth_list.inc');
    $menu_auth = $menuAuthStorage->get_member_array($auth->uid);

    # 메뉴에 접근 권한이 있는 지 체크
    if($activity!='index'){
        if($auth->level<99){
            if($menu_auth!==false){
                if(!in_array($_REQUEST['mid'],$menu_auth)) {
                   Out::history_go($res->strings['err_auth']);
                }
            }
        }
    }

    # 메뉴 출력 결정
    if($menu_auth !== false)
    {
        if($auth->level<99){
            foreach($menu as $mindex =>$menu_args) {
                if(!in_array($menu_args['mid'],$menu_auth))
                   unset($menu[$mindex]);
            }

            foreach($sub_menu as $sindex => $sub_menu_args) {
                if(!in_array($sub_menu_args['mid'],$menu_auth))
                    unset($sub_menu[$sindex]);
                if($sub_menu_args['mid'] == '0002' && $auth->level < 99) {
                    unset($sub_menu[$sindex]);
                }
            }
        }
    }
}

# tpl 변수
$tpl['strings']  = $res->strings;
$tpl['navi']     = $menu;
$tpl['navi_sub'] = $sub_menu;

# prints
$tpl->compile_dir =_ROOT_PATH_.'/'._TPL_.'/admin';
$tpl->compile     = true;
$tpl->compression = false;
$tpl->display();
?>