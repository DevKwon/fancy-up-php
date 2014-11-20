<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# classes 디렉토리 아래 폴더만 가져오기
Out::prints_ln('classes 디렉토리 아래 폴더만 가져오기/--------------');
$dirObject = new DirObject(_ROOT_PATH_.'/classes');
Out::prints_r($dirObject->findFolders());

# classes/template 디렉토리 아래 파일만 가져오기
Out::prints_ln('classes/template 디렉토리 아래 파일만 가져오기/--------------');
$dirObject = new DirObject(_ROOT_PATH_.'/classes/template');
Out::prints_r($dirObject->findFiles());

# install 디렉토리 아래 php 파일만 가져오기
Out::prints_ln('install 디렉토리 아래 php 파일만 가져오기/--------------');
$dirObject = new DirObject(_ROOT_PATH_.'/install');
Out::prints_r($dirObject->findFiles('*.php'));
?>