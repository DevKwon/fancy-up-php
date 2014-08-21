<?php
/** ================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-16
----------------------------------------------------------*/
final class Res
{
    private $nation=_LANG_; // 국가코드
    
    // resource 값
    private $strings = array();
    private $manifest=array();
    private $resource;
	
	# 배열값 추가 등록    
    public function __construct($nation=''){
        if($nation){
            $this->nation = $nation;
        }
             
        # 기본 환경설정 xml
        $string_obj=new XmlSimple(_ROOT_PATH_.'/'._VALUES_.'/strings_'.$this->nation.'.xml');
        $string_xml=$string_obj->fetch($string_obj->query('resources'));
        $this->strings=&$string_xml[0];
        
        # resource 객체화 시키기
        $this->resource = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
    }
    
    #@ void
    # XML 데이타 추가 관리
    # $filename : XML 파일 전체 경로
    # $query : XML 쿼리
    # ex)
    # $res->setResource(_ROOT_PATH_.'/'._QUERY_.'/cp_querys.xml', 'tables');
    # print_r($res->resource->tables);
    # echo $res->resource->tables['member'];
    public function setResource($filename, $query)
    {
        if(!$query)
            Out::prints_json(array('result'=>'false', 'msg'=>'empty : $query'));
        
        # xml 파일
        $resource_obj=new XmlSimple($filename);
        $resource_xml=$resource_obj->fetch($resource_obj->query($query));
        $this->resource->{$query}=&$resource_xml[0];
    }
    
    #@ return array
    # 프라퍼티 값 포함한 가져오기
    public function __get($propertyName){
        if(property_exists(__CLASS__,$propertyName)){
            return $this->{$propertyName};
        }
    }
}
?>