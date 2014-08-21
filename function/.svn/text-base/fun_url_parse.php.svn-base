<?php

# @Array
# 주소의 변수 값을 배열로 리턴한다.
function url_query_parse_array($_query_string)
{
	$_params = array();
	if($_query_string!='')
	{
		if(strpos($_query_string,'&') !==false){
			$parseUrls_tmp = explode('&', $_query_string);
			foreach($parseUrls_tmp as $puk => $puv){
				$parseUrls = explode('=', $puv);
				$_params[$parseUrls[0]] =$parseUrls[1];
			}
		}else{
			$parseUrls = explode('=', $_query_string);
			$_params[$parseUrls[0]] =$parseUrls[1];
		}
	}
return $_params;
}
?>