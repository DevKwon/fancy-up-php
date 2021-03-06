<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 기본 strings_ko.xml resources
print_r($res->strings);

# manifest xml resource
$res->setResource(_ROOT_PATH_.'/'._QUERY_.'/querys.xml', 'tables');
print_r($res->resource->tables);
echo $res->resource->tables['member'];

# manifest xml resource
$res->setResource(_ROOT_PATH_.'/'._XML_.'/manifest.xml', 'activity');
print_r($res->resource->activity);

# manifest xml resource
$res->setResource(_ROOT_PATH_.'/'._VALUES_.'/array.xml', 'array_email');
Out::prints_r($res->resource->array_email);

# manifest xml resource
$res->setResource(_ROOT_PATH_.'/'._VALUES_.'/integers.xml', 'integer');
Out::prints($res->resource->integer['pay_price']);

# resource
# setResourceRoot 같은 경우에는 2번째 파라메터는 inc, test 등과 같이 내가 네이밍을 결정할 수 있습니다.
$res->setResourceRoot(_ROOT_PATH_.'/'._MENU_.'/menu_admin.xml', 'menu');
print_r($res->resource->menu);

Out::prints_ln('포트폴리오 /===============================');
$res->setResourceRoot(_ROOT_PATH_.'/'._VALUES_.'/portfolio.xml', 'portfolio');
print_r($res->resource->portfolio);



?>