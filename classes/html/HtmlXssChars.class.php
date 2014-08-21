<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @version:1.0
----------------------------------------------------------*/

# purpose : xss 방지 및
class HtmlXssChars
{
	private $description;
	private $allow_tags = array();
	
	public function __construct($description){
		$this->description = $description;
	}
	
	# 허용 태그 설정
	public function setAllowTags($value){
		if(is_array($value)) $this->allow_tags = array_merge($this->allow_tags,$value);
		else $this->allow_tags[] = $value;
	}
	
	# strip_tags
	public function cleanTags(){
		return strip_tags($this->description,implode('', $this->allow_tags));
	}
	
	# Xss 태그 처리
	public function cleanXssTags(){
		$xss_tags = array(
			'@<script[^>]*?>.*?</script>@si',
			'@<style[^>]*?>.*?</style>@siU',	
			'@<iframe[^>]*?>.*?</iframe>@si',
			'@<meta[^>]*?>.*?>@si',
			'@<form[^>]*?>.*?>@si',
			'@]script[^>]*?>.*?]/script>@si',	// [\xC0][\xBC]script>[code][\xC0][\xBC]/script>
			'/:*?expression\(.*?\)/si',
			'/:*?binding:(.*?)url\(.*?\)/si',
			'/javascript:[^\"\']*/si',
			'/vbscript:[^\"\']*/si',
			'/livescript:[^\"\']*/si',
			'@<![\s\S]*?--[ \t\n\r]*>@'// multi-line comments including CDATA
		);
		
		$event_tags = array(
			'dynsrc','datasrc','frameset','ilayer','layer','applet',
			'onabort','onactivate','onafterprint','onsubmit','onunload',
			'onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut',
			'onbeforedeactivate','onbeforeeditfocus','onbeforepaste','onbeforeprint',
			'onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange',
			'onchange','onclick','oncontextmenu','oncontrolselect','oncopy','oncut',
			'ondataavaible','ondatasetchanged','ondatasetcomplete','ondblclick',
			'ondeactivate','ondrag','ondragdrop','ondragend','ondragenter',
			'ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
			'onfilterupdate','onfinish','onfocus','onfocusin','onfocusout','onhelp',
			'onkeydown','onkeypress','onkeyup','onlayoutcomplete','onload','onlosecapture',
			'onmousedown','onmouseenter','onmouseleave','onmousemove','onmoveout',
			'onmouseover','onmouseup','onmousewheel','onmove','onmoveend','onmovestart',
			'onpaste','onpropertychange','onreadystatechange','onreset','onresize',
			'onresizeend','onresizestart','onrowexit','onrowsdelete','onrowsinserted',
			'onscroll','onselect','onselectionchange','onselectstart','onstart','onstop'
		);
		
		// 허용 태그 확인
		if(is_array($this->allow_tags)){
			$tmp_eventag= str_replace($this->allow_tags,'',implode('|',$event_tags));
			$event_tags = explode('|',$tmp_eventag);
		}

		return preg_replace($xss_tags, '', str_ireplace($event_tags,'_badtags',$this->description));
	}

	# 자동 링크 걸기
	public function setAutoLink()
	{
		$homepage_pattern = "/([^\"\'\=\>])(mms|market|http|https|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/";
		$this->description = preg_replace($homepage_pattern,"\\1<a href=\\2://\\3 target='_blank'>\\2://\\3</a>",' '.$this->description);

		// 메일 치환
		$email_pattern = "/([ \n]+)([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)/";
		return preg_replace($email_pattern,"\\1<a href=mailto:\\2@\\3>\\2@\\3</a>", " ".$this->description);
	}

	public function setHttpUrl($url)
	{
		$url = (!$url) ? trim($url) : $url;

		##	기본적으로 넘어온 URL에 프로토콜을 나타내는 부분이 있는지 확인하여 http:// 를 붙인다.
		if(!eregi("^(http://|https://|ftp://|telnet://|news://)", $url)) $url = eregi_replace("^",'http://', $url);
		
		$url = eregi_replace("http.*://", '', $url);
		//$url = $type ? eregi_replace("^", "http://", $url) : $url;
 		return $url = eregi_replace("^", "http://", $url);
	}
	
	public function getContext($mode='XSS')
	{
		$this->description =stripslashes($this->description);
		switch(strtoupper($mode)){
			case 'TEXT':
				$this->description = str_replace("&nbsp;",' ',$this->description);
				$this->description = str_replace("\r\n","\n",$this->description);
				$this->description = str_replace("\n","<br />",$this->description);
				$this->description = self::setAutoLink();
				break;
			case 'XSS':
				$this->description = str_replace("\r\n","\n",$this->description);
				$this->description = str_replace("\n","<br />",$this->description);
				$this->description = self::setAutoLink();
				$this->description = self::cleanXssTags();
				#$this->description = htmlspecialchars($this->description);
				break;
			case 'HTML':
				#$this->description = htmlspecialchars($this->description);
				$this->description = str_replace("\r\n","\n",$this->description);
				$this->description = str_replace("\n","<br />",$this->description);
				$this->description = self::setAutoLink();
				break;				
		}
	return $this->description;
	}
}
?>