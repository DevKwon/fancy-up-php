<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-16,2012-05-16
----------------------------------------------------------*/

/**
$sx =new XmlSimple('경로/main.xml');
$result=$sx->query('navigation');
$args1=$sx->fetch($result);
*/
class XmlSimple
{
	public $xml_obj;
	private $num_rows = 0;	# query 엘리멘트 갯수

	#@ void
	# xpath = xml 파일 경로
	public function __construct($xpath){
		if(!self::isExists($xpath))
				throw new ErrorException(Status::getStatusMessage('404'),__LINE__);

		# 데이타 가져오기
		if(!$xml_data = file_get_contents($xpath))
				throw new ErrorException('No data',__LINE__);

		# simple xml
		$this->xml_obj = new SimpleXMLElement($xml_data);
	}

	#@ return xml array
	# navigation/item
	public function query($query){
		$result=$this->xml_obj->xpath($query);
		$this->num_rows =count($result);
	return $result;
	}

	#@return array
	public function fetch($result)
	{
		$loop =array();
		for($i=0; $i<$this->num_rows; $i++)
		{
			// attributes만 있을 수 있음
			$attr_count=count($result[$i]->attributes());
			if($attr_count>0)	{
				$loop2=&$loop[$i]['attr'];
				foreach($result[$i]->attributes() as $k2=>$kv){
					$loop2[$k2]=strval($kv);
				}
			}

			// child
			if(count($result[$i])>0)
			{
				foreach($result[$i] as $k=>$v)
				{
					// child -> attributes
					$attr_count=count($v->attributes());
					if($attr_count>0)	{
						$loop3=&$loop[$i][$k][]['attr'];
						foreach($v->attributes() as $k3=>$k3v){
							$loop3[$k3]=strval($k3v);
						}
					}else{
						$loop[$i][$k]=strval($v);
					}
				}
			}else{
				return strval($result[$i]);
			}
		}
	return $loop;
	}

	#@ return
	public function __get($propertyName){
		if(property_exists(__CLASS__,$propertyName)){
			return $this->{$propertyName};
		}
	}

	# 로컬 파일인지 체크
	protected function isExists($filename){
		if(!file_exists($filename)) return false;
	return true;
	}
}
?>