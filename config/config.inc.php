<?php
/** ======================================================
| @Author	: 김종관
| @Email	: apmsoft@gmail.com
| @Editor	: Eclipse(default)
| @UPDATE	: 12-06-30
----------------------------------------------------------*/
session_start();

# $path 경로 설정 필요 및 설정
define('_ROOT_PATH_',$path);
define('_CHRSET_','utf-8');

# 기본 설정
define('_LIB_','lib');              #가공되지 않은 API(한번 적용되면 쉽께 삭제할 수 없음)
define('_PLUGINS_','plugins');      #가공되지 않은 API등(언제든 넣고 뺄수 있음)
define('_COMMON_','common');        #js,css
define('_MODULES_','modules');      #게시판,회원가입등 솔루션에 해당하는 모듈
define('_SRC_','src');              #PHP 파일 프로그램 폴더

# 리소스
define('_RES_','res');              #테이블명 및 쿼리문
define('_QUERY_',_RES_.'/query');   #테이블명 및 쿼리문
define('_VALUES_',_RES_.'/values'); #데이터 타입이 확실한
define('_RAW_',_RES_.'/raw');       #가공되지 않은 원천 내용
define('_MENU_',_RES_.'/menu');     #메뉴
define('_XML_',_RES_.'/xml');       #설정 및
define('_LAYOUT_',_RES_.'/layout'); #웹사이트 ROOT LAYOUT (html,js,css,image)

# 데이터 업로드 및 캐슁파일
define('_DATA_','_data');
define('_UPLOAD_','_data/files');
define('_TPL_','_data/tpl');

# 데이타베이스 정보
include_once _ROOT_PATH_.'/config/config.db.php';

# 클래스 자동 인클루드
function __autoload($class_name){
    $tmp_args=explode(' ',preg_replace('/([a-z0-9])([A-Z])/',"$1 $2",$class_name));
    $class_path_name=sprintf("%s/{$class_name}",strtolower($tmp_args[0]));
    if(!class_exists($class_path_name,false))
    {
        # classes 폴더
        if(file_exists(_ROOT_PATH_.'/classes/'.$class_path_name.'.class.php')!==false){
            include_once _ROOT_PATH_.'/classes/'.$class_path_name.'.class.php';
        }
        # modules 폴더
        else if(file_exists(_ROOT_PATH_.'/'._MODULES_.'/'.$class_path_name.'.class.php')!==false){
            include_once _ROOT_PATH_.'/'._MODULES_.'/'.$class_path_name.'.class.php';
        }
        # src 폴더
        else if(file_exists(_ROOT_PATH_.'/'._SRC_.'/'.$class_path_name.'.class.php')!==false){
            include_once _ROOT_PATH_.'/'._SRC_.'/'.$class_path_name.'.class.php';
        }

        # 기타 만든 클래스 폴더 [첫대문자만 폴더로 지원]
        # ( /my/MyTest.class.php -> MyTest.class.php)
        else if(file_exists(_ROOT_PATH_.'/'.$class_path_name.'.class.php')!==false){
            include_once _ROOT_PATH_.'/'.$class_path_name.'.class.php';
        }
    }
}

# 함수 자동 인클루드
# function 디렉토리에 있어야 하며 클래스를 지원하는 함수들
# 파일명 규칙 (_*.helper.php)
$__autoload_helper_funs = array(
    '_reqstrchecker'
);
if(is_array($__autoload_helper_funs)){
    foreach($__autoload_helper_funs as $fun_name){
        $tmp_fun_filename = _ROOT_PATH_.'/function/'.$fun_name.'.helper.php';
        if(file_exists($tmp_fun_filename)!==false){
            include_once $tmp_fun_filename;
        }
    }
}

# 기본 선언 클래스
# 어플리케이션 환경
$app=new ApplicationEnviron();

define('_LANG_',$app->lang);       # 언어
define('_SITE_HOST_',$app->host);  # HOST URL

# resources ::/res/values/strings_ko.xml 자동
$res=new Res();
?>