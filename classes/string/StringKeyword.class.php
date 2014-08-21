<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-04
----------------------------------------------------------*/

# purpose : 문자을 변경하거나 더하거나 등 가공하는 역할을 한다.
class StringKeyword
{
	private $keywords;
	
	public function __construct($keyword){
		if(!$keyword) return false;
		$this->keywords=self::cleanWord($keyword);
	}
	
	# 특수문자 제거 및 단어별 배열로 리턴
	# return array()
	private function cleanWord($keywords)
	{
		$keywords = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i",' ',$keywords); 
		$keywords = preg_replace('/\s\s+/', ' ', $keywords); // 연속된 공백을 하나로 제거
		$keywords = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/",$keywords, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		return array_unique($keywords);
	}

	#@ return String
	# 키워드를 가지고 선택된 필드로 디비 검색 문장을 만든다
	# 복수필드 name+category+area
	public function set_where($fields, $andor = 'OR')
	{
		$result = '';
		if(!$fields || !$this->keywords) return $result;

		// filed
		$field_args = array();
		$fields = (strpos($fields, '+') !==false) ? str_replace('+', ' ', $fields) : $fields;
		if(strpos($fields, ' ') !== false) $field_args = explode(' ',$fields);
		else $field_args[] = trim($fields);

		// 키워드
		if(is_array($this->keywords))
		{
			$cntField = count($field_args);# 필스 갯수
			for($fi=0; $fi<$cntField; $fi++)
			{
				$count=count($this->keywords);
				for($i=0; $i<$count; $i++){
					if($this->keywords[$i]){
						$result.= " `".$field_args[$fi]."` LIKE '%".$this->keywords[$i]."%' ".$andor;
					}
				}
			}// field end

		return substr($result,0,-2);
		}
	return $result;
	}
	
	#@ return
	# keywords 값 배열 리턴
	public function get_keywords(){
		return $this->keywords;
	}
}
?>