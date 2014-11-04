<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# lib / phpQuery
include_once(_ROOT_PATH_.'/'._LIB_.'/phpQuery-0.9.5.386.php');

$content = '팝업 테스트중.. (신경 끄삼)<img src="/_data/files/popup/2014/10/m/2014_10_0_08400100_1413958137.jpg" width="100%"><br><br><br />
굉장한 우리나라 이죠<a href="#" title="타이틀"><img src="test.jpg" /></a><br> 역시 세상은 살만 한거임?????? 글侍?
<a href="http://naver.com" title="네이버">네이버</a> 네이버는 좋겠어 ㅋㅋㅋ
<div class="post">
    <ul id="thumb_ul" class="star-rating" style="width:60px;">
        <li class="current-rating" style="width:0px;">li1</li>
         <li class="current-rating" style="width:6px;">li2</li>
          <li class="current-rating" style="width:8px;">li3</li>
    </ul>
</div>';

$doc = phpQuery::newDocumentHTML($content);
//var_dump($phpQ);

# 텍스트만 추출된 데이터
Out::prints_ln('텍스트만 추출 /================');
Out::prints_ln($doc->document->textContent);

# image /=================
Out::prints_ln( '이미지 태그 /================' );


$imgs= pq('img');
foreach ($imgs as $img) {
  Out::prints_ln( pq($img)->attr('src') );
  Out::prints_ln( pq($img)->attr('width') );
}

# 내용에서 'test.jpg'를 포함한 이미지 태그 삭제
$doc->find("img[src*='/_data/files/popup/2014/10/m/2014_10_0_08400100_1413958137.jpg']")->remove();
Out::prints_ln( '이미지 태그 삭제 후 /================' );
Out::prints_ln( $doc->html() );


# a /===========================
Out::prints_ln( ' A 태그 /=====================' );
$as= pq('a');
foreach ($as as $a) {
	Out::prints_ln( pq($a)->attr('href') );
    Out::prints_ln( pq($a)->attr('title') );
}

# 기타 1
Out::prints_ln( ' 기타1 태그 /=================' );
$exam1 = pq('.post ul li.current-rating');
foreach ($exam1 as $li) {
	Out::prints_ln( pq($li)->attr('style') );
}
?>