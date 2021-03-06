<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-16
----------------------------------------------------------*/

# RSS 2.0 리더기
class XmlRss2
{
	private $parser;
	private $encoding = '';

	private $sTag	= '';
	private $eTag	= '';
	private $channel=array();

	private $items	= array();
	private $item		= array();

	private $images	= array();
	private $image	= array();
	
	#@ url : 주소
	public function __construct($url="", $data="")
	{
		if(!empty($url))
			$this->getUrlData($url);
		else if(!empty($data))
			$this->getData($data);
		else
			throw new ErrorException(__CLASS__.' 주소를 입력해 주세요', __LINE__);
    }

	// 데이타로 추출
	private function getData($data){
		# encoding /--
		if (!$this->encoding)
		{
			$tmp_encoding = preg_replace('/<\?xml version=(.*) encoding=(.*)\?>/i', '\\2', $data);
			$tmp_encoding = trim($tmp_encoding);
			$tmp_encoding = str_replace('"', '', $tmp_encoding);
			$tmp_encoding = str_replace("'", '', $tmp_encoding);
			$this->encoding = strtoupper($tmp_encoding);
		}

		$this->_xml_create();
		$this->_xml_parse($data);
	}

	// 파일 주소로 데이타 추출
	private function getUrlData($url){
		$fp = @fopen($url,'r');
		if (!$fp) throw new ErrorException(__CLASS__.' fopen error ', __LINE__);

		while (!@feof ($fp))
		{
			$data .= @fgets($fp, 4096);
			
			# encoding /--
			if (!$this->encoding)
			{
				$tmp_encoding = preg_replace('/<\?xml version=(.*) encoding=(.*)\?>/i', '\\2', $data);
				$tmp_encoding = trim($tmp_encoding);
				$tmp_encoding = str_replace('"', '', $tmp_encoding);
				$tmp_encoding = str_replace("'", '', $tmp_encoding);
				$this->encoding = strtoupper($tmp_encoding);
			}
		}
		@fclose ($fp);

		$this->_xml_create();
		$this->_xml_parse($data);
	}

	private function _xml_create()
	{
		$this->parser = @xml_parser_create();
		if (is_resource($this->parser))
		{
			xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
			xml_set_object($this->parser, &$this);
			xml_set_element_handler($this->parser, 'startElement', 'endElement');
			xml_set_character_data_handler($this->parser, 'characterData');
        }
    }

	private function _xml_parse($data){
		@xml_parse($this->parser,$data);
	}

	// 메모리 비우기
	private function _xml_free()
	{
		if (is_resource($this->parser)){
			xml_parser_free($this->parser);
			unset( $this->parser );
		}
	}

	private function startElement($parser, $name, $attr)
	{
		switch ($name)
		{
			case 'channel':
			case 'item':
			case 'image':
			case 'textinput':
				$this->sTag = $name;
				break;
			default:
				$this->eTag = $name;
		}
	}

    private function endElement($parser, $name)
	{		
		if ($name == $this->sTag){
            $this->sTag = '';
        }

        if ($name == 'item'){
            $this->items[] = $this->item;
            $this->item = '';
        }

        if ($name == 'image'){
            $this->images[] = $this->image;
			$this->image = '';
        }

        $this->eTag = '';
    }


    private function characterData($parser, $data)
	{
		$tagName= $this->sTag;
		$field		= $this->eTag;
		
		if (trim($data)){
			if(!empty($tagName)){
				$this->{$tagName}[$field].= $data;
				$this->point = $this->{$tagName};
			}
		}
	}

	public function __get($propertyname){
		return $this->{$propertyname};
	}

    public function getChannel(){
        return $this->channel;
    }

    public function getItems(){
        return $this->items;
    }

    public function getImages(){
        return $this->images;
    }

	// 비우기
	public function __destruct(){
    	$this->channel=array();
		$this->items= array();
		$this->item= array();
		$this->images= array();
		$this->image= array();
    }
}
?>
