<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 세션 선언
$auth =new AuthSession();
$auth->sessionStart();

# 세션키 생성 AuthSession(www String  | admin String)
$auth=new AuthSession();
// $auth->regiAuth(array(
//     'auth_uid'=>'1',
//     'auth_userid'=>'fancy-up',
//     'auth_name'=>'개발자',
//     'auth_nickname' => 'AX개발자'
// ));

// $auth=new AuthSession('admin);
// $auth->regiAuth(array(
    // 'admin_uid'=>'1',
    // 'admin_userid'=>'fancy-up-admin',
    // 'admin_name'=>'개발자 관리자',
    // 'admin_nickname' => 'AX개발자 관리자'
// ));

# 세션키 출력
Out::prints_ln('auth_uid : '.$auth->uid);
Out::prints_ln('auth_userid : '.$auth->userid);
Out::prints_ln('auth_name : '.$auth->name);
Out::prints_ln('auth_nickname : '.$auth->nickname);
?>