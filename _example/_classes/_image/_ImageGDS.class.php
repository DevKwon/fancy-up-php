<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

################
## 활용법
################

try{
    $gd = new ImageGDS(_ROOT_PATH_.'/'._DATA_.'/2014_09_0_93548500_1411378158.jpg');
    Out::prints_ln( 'gd버전: '.$gd->getVersion());

    # 사진 이미지 사이즈
    $img_info = $gd->getImageSize();
    Out::prints_ln( '원본 사진크기 : '.$img_info->width.' x '.$img_info->height);

    # 썸네일 이미지 만들기
    Out::prints_ln('썸네일 이미지 만들기 120x120');
    $gd->thumbnailImage(120,120);
    $gd->write(_ROOT_PATH_.'/'._DATA_.'/thumb.jpg');
    Out::prints_ln('<img src="/'._DATA_.'/thumb.jpg" />');

    # 이미지 자르기
    Out::prints_ln('이미지 자르기 500x150,x:150,y:100');
    $gd->cropImage(500,150,150,100);
    $gd->write(_ROOT_PATH_.'/'._DATA_.'/crop.jpg');
    Out::prints_ln('<img src="/'._DATA_.'/crop.jpg" />');

    # 이미지 자르기 썸네일
    Out::prints_ln( '이미지 자르기 썸네일 120x120');
    $gd->cropThumbnailImage(120,120);
    $gd->write(_ROOT_PATH_.'/'._DATA_.'/cropthumb.jpg');
    Out::prints_ln( '<img src="/'._DATA_.'/cropthumb.jpg" />');

    # 필터 워터마크 찍기
    Out::prints_ln( '필터 워터마크 찍기');
    $gd = new ImageGDS(_ROOT_PATH_.'/'._DATA_.'/crop.jpg');
    $gd->filterWatermarks(_ROOT_PATH_.'/'._DATA_.'/thumb.jpg');
    $gd->write(_ROOT_PATH_.'/'._DATA_.'/watermark.jpg');
    Out::prints_ln( '<img src="/'._DATA_.'/watermark.jpg" />');


    Out::prints_ln( '타이틀 이미지 만들기');
    $gd = new ImageGDS();
    $gd->setBgColor(0x7fffffff);
    $gd->setFont(_ROOT_PATH_.'/_example/HYSUPM.TTF');
    $gd->setFontColor(array(0,0,0));
    $gd->setFontSize(20);
    $gd->setXY(5,40);
    $gd->writeTextImage(500,60,'김형오 의장, 설 앞두고 용산노인복지관');
    $gd->write(_ROOT_PATH_.'/'._DATA_.'/textimage.png');
    Out::prints_ln( '<img src="/'._DATA_.'/textimage.png" />');

    Out::prints_ln( '이미지 위에 글씨 넣기');
    $gd = new ImageGDS(_ROOT_PATH_.'/'._DATA_.'/watermark.jpg');
    $gd->setFont(_ROOT_PATH_.'/_example/HYSUPM.TTF');
    $gd->setFontColor(array(255,255,255));
    $gd->setFontSize(20);
    $gd->setXY(5,40);
    $gd->combineImageText(500,60,'김형오 의장, 설 앞두고 용산노인복지관');
    $gd->write(_ROOT_PATH_.'/'._DATA_.'/combineimagetext.png');
    Out::prints_ln( '<img src="/'._DATA_.'/combineimagetext.png" />');


    Out::prints_ln( '그림자 텍스트 이미지');
    $gd = new ImageGDS();
    $gd->setFont(_ROOT_PATH_.'/_example/HYSUPM.TTF');
    $gd->setFontSize(20);
    $gd->setXY(5,40);
    $gd->writeShadowText(500,60,'연중돌봄학교로 ‘제2의 개교’ 맞는 고창성송초');
    $gd->write(_ROOT_PATH_.'/'._DATA_.'/shadowtext.png');
    Out::prints_ln( '<img src="/'._DATA_.'/shadowtext.png" />');

}catch(Exception $e){
    echo $e->getMessage();
}
?>