<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-16
----------------------------------------------------------*/

class HtmlWWWCollect
{
    private $requestHeader= '';			// 헤더 저장
    private $contents		= '';			// 페이지 전체 내용 저장
    private $collect_varz		= array();		// 결과가 저장될 배열

    private $_arr_no			= 0;			// 결과배열 번호 
    private $_colnum		= 0;			// 검색 시작 번지
	private $_pattern		= array();
    private $_set_cookies	= '';
    private $_set_referer	= '';
    private $_start_collect	= array();
	private $_replace_str	= '';

	public function __construct($url,$method='GET',$port='80')
	{
		if($method != 'GET' && $method != 'POST')
			throw new ErrorException("method 값은 GET 또는 POST로만 사용하세요",__LINE__);

        $url_info = parse_url($url);
        $fp = fsockopen($url_info['host'],$port, $errno, $errstr);
		if(!$fp){
            throw new ErrorException($errstr, $errno);
        }
         
        if($method == 'POST')
		{
            fputs($fp,'POST '.$url_info['path'].' HTTP/1.0'."\r\n");
            fputs($fp,'Host: '.$url_info['host']."\r\n");
            fputs($fp,'User-Agent: PHP Script'."\r\n");

            if($this->_set_referer)
                fputs($fp,'Referer: '.$this->_set_referer."\r\n");

            if($this->_set_cookies)
                fputs($fp,'Cookie: '.$this->_set_cookies."\r\n");

            fputs($fp,'Content-Type: application/x-www-form-urlencoded'."\r\n");
            fputs($fp,'Content-Length: '.strlen($url_info['query'])."\r\n");
            fputs($fp,'Connection: close'."\r\n\r\n");
            fputs($fp,$url_info['query']);
        }
        
		else
		{
            fputs($fp,'GET '.$url_info['path'].($url_info['query'] ? '?'.$url_info['query'] : '').' HTTP/1.0'."\r\n");
            fputs($fp,'Host: '.$url_info['host']."\r\n");
            fputs($fp,'User-Agent: PHP Script'."\r\n");

            if($this->_set_referer)
                fputs($fp,'Referer: '.$this->_set_referer."\r\n");

            if($this->_set_cookies)
                fputs($fp,'Cookie: '.$this->_set_cookies."\r\n");

            fputs($fp,'Connection: close'."\r\n\r\n");
        }

        $this->contents = '';
        $this->requestHeader = '';

        # 응답헤더 /--
		while(trim($col_varz = fgets($fp,1024)) != ''){
            $this->requestHeader .= $col_varz;
        }

        #  내용 읽기 /--
		while(!feof($fp))
		{
            $fcontents = fgets($fp,1024);
			if($this->_replace_str)
			{
				foreach($this->_replace_str as $k=>$v){
					$fcontents = str_replace($this->_replace_str[$k]['strv'], $this->_replace_str[$k]['restrv'],$fcontents);
				}

				$this->contents .=  $fcontents;
			}else{
				$this->contents .=  $fcontents;
			}
        }
        fclose($fp);
	}

	// 전체 데이타 반환
	public function getDescription(){
		return $this->contents;
	}

    # pattern |-------------
	# 필드명,시작문자,끝문자,지울문자
	private function set_Pattern($field,$str_s,$str_e,$str_del=''){
		$this->_pattern[$field] = array($str_s,$str_e,$str_del);
    }

	# 특정문자를 \r\n으로 변경 시켜주기 |--------
	private function set_StrPlace($field,$strv, $restrv){
		$this->_replace_str[$field]['strv'] = $strv;
		$this->_replace_str[$field]['restrv']=$restrv;
	}
   
	# 파싱을 시작함시작위치를 설정 |-------------
	private function set_StartCollect($strline){
        $this->_start_collect = $strline;
    }

    # 쿠키설정 |-------------------------------------------
	private function set_Cookie($var,$value){
        $this->_set_cookies .= $var.'='.urlencode($value).';'; 
    }

    # 레퍼러를 설정합니다 |--------------------------------
	private function set_Referer($ref){
        $this->_set_referer = $ref;
    }

	public function get_ParsePage()
	{		
		$key_arr = array_keys($this->_pattern);
        $count=count($key_arr);
		
		for ($i=0; $i<$count; $i++){
			$this->get_Text($key_arr[$i]);
		}
    }

    # 검색위치를 건너뛰는 부분 |-----------------------------------
	public function get_colnum($str)
	{
        $tmp_colnum = strpos($this->contents,$str,$this->_colnum);
        
		if($tmp_colnum)
            $this->_colnum = $tmp_colnum + strlen($str);
        else
            $this->_loop = 0;
    }

    # 패턴 사이 문자열 추출 |---------------------------
	public function get_Text($field,$str='')
	{
		$line = ($str)?$str:$this->contents;
        
		$sn = strpos($line,$this->_pattern[$field][0]);        
		$sn += strlen($this->_pattern[$field][0]);

        $en = strpos($line,$this->_pattern[$field][1]);

		$str = substr($line,$sn,$en-$sn);
		if(trim($str)){
			if($this->_pattern[$field][2])
				$this->collect_varz[][$field] = str_replace($this->_pattern[$field][2],'',trim($str));
			else
				$this->collect_varz[][$field] = trim($str);
		
		# 총갯수
		$this->_arr_no++;
		}
    }

	// 비우기
	public function __destruct(){
    	$this->contents = null;
		$this->collect_varz=null;
		$this->_pattern= array();
		$this->_start_collect= array();
    }
} 
?>