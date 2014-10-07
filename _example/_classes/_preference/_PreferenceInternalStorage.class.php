<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

$tmp_args = array(
    array('row1_1'=>'field1','row1_2'=>'field2'),
    array('row2_1'=>'field1','row2_2'=>'field2')
);

# 파일 쓰기
Out::prints_ln('json -> string');
$preferenceObj = new PreferenceInternalStorage(_ROOT_PATH_.'/'._DATA_.'/test.txt','w');
$preferenceObj->writeInternalStorage(strval(json_encode($tmp_args)));

# 파일 읽어오기
Out::prints_ln('string -> json');
$preferenceObj = new PreferenceInternalStorage(_ROOT_PATH_.'/'._DATA_.'/test.txt','r');
Out::prints_r(json_decode($preferenceObj->readInternalStorage()));

# 파일 csv 쓰기
Out::prints_ln('csv 저장');
$preferenceObj = new PreferenceInternalStorage(_ROOT_PATH_.'/'._DATA_.'/test.csv','w');
$preferenceObj->writeInternalStorageCSV($tmp_args);

# 파일 csv 읽기
Out::prints_ln('csv -> array');
$preferenceObj = new PreferenceInternalStorage(_ROOT_PATH_.'/'._DATA_.'/test.csv','r');
Out::prints_r($preferenceObj->readInternalStorageCSV());
?>