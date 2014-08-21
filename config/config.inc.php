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
define('_LANG_','ko');

# 기본 설정
define('_PLUGINS_','plugins');
define('_COMMON_','common');
define('_MODULES_','modules');
define('_SRC_','src');

# 리소스
define('_QUERY_','res/query');
define('_VALUES_','res/values');
define('_XML_','res/xml');
define('_LAYOUT_','res/layout');

# 데이터 업로드 및 캐슁파일
define('_DATA_','_data');
define('_UPLOAD_','_data/files');
define('_TPL_','_data/tpl');

# 기타 프로그램설정정보
define('_SITE_HOST_','http://fancyup.c1p.kr');

# 데이타베이스 정보
include_once _ROOT_PATH_.'/config/config.db.php';

# API
define('_GCM_API_KEY_','AIzaSyCHYebMTKBJ9jh15xPbyBfWG4Yv9ZPU7qM');

# 클래스 자동 인클루드
function __autoload($class_name){
    $tmp_args=explode(' ',preg_replace('/([a-z0-9])([A-Z])/',"$1 $2",$class_name));
    $class_path_name=sprintf("%s/{$class_name}",strtolower($tmp_args[0]));
    if(!class_exists($class_path_name,false)){
        # classes 폴더
        if(file_exists(_ROOT_PATH_.'/classes/'.$class_path_name.'.class.php')!==false){
            include_once _ROOT_PATH_.'/classes/'.$class_path_name.'.class.php';
        }
        # modules 폴더
        else if(file_exists(_ROOT_PATH_.'/'._MODULES_.'/'.$class_path_name.'.class.php')!==false){
            include_once _ROOT_PATH_.'/'._MODULES_.'/'.$class_path_name.'.class.php';
        }
        # 기타 만든 클래스 폴더 [첫대문자만 폴더로 지원]
        # ( /my/MyTest.class.php -> MyTest.class.php)
        else if(file_exists(_ROOT_PATH_.'/'.$class_path_name.'.class.php')!==false){
            include_once _ROOT_PATH_.'/'.$class_path_name.'.class.php';
        }
    }
}

# 전체 리소스::/res/values/strings_ko.xml 자동
$res=new Res();
?>