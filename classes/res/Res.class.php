<?php
/** ================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 1.1.2
----------------------------------------------------------*/
final class Res
{
    private $nation=_LANG_; // 국가코드

    // resource 값
    private $strings = array();
    private $resource;

	# 배열값 추가 등록
    public function __construct($nation=''){
        if($nation){
            $this->nation = $nation;
        }

        # 기본 환경설정 xml
        $string_obj=new XmlSimpleXMLElementPlus(_ROOT_PATH_.'/'._VALUES_.'/strings_'.$this->nation.'.xml', null, true);
        $string_xml = $string_obj->xpath('resources');
        $this->strings= (array)$string_xml[0];

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
        if(!$query) throw new ErrorException(__CLASS__.' :: '.__LINE__.' '.$query.' is null');

        # xml 파일
        $resource_obj=new XmlSimpleXMLElementPlus($filename, null, true);
        if($resource_obj->isNullChild($query)) return false;

        $result = $resource_obj->xpath($query);
        if(is_array($result))
        {
            $res_root = array();
            $res_root =self::xml2Array($result[0],false);
            $this->resource->{$query} =&$res_root;
        }
    }

    #@ void
    # XML 데이타 추가 관리
    # $filename : XML 파일 전체 경로
    # $elementName : $res->resource->{리소스네임}
    # $res->setResourceRoot(_ROOT_PATH_.'/'._QUERY_.'/cp_querys.xml', '리소스네임');
    public function setResourceRoot($filename, $elementName)
    {
        # xml 파일
        $resource_obj=new XmlSimpleXMLElementPlus($filename, null, true);
        if(is_object($resource_obj))
        {
            $res_root = array();
            $res_root= self::xml2Array($resource_obj,$res_root);
            $this->resource->{$elementName} =&$res_root;
        }
    }

    #@ return array
    private function xml2Array($xml, $root = true)
    {
        if (!$xml->children()) {
            return (string)$xml;
        }

        $array = array();
        foreach ($xml->children() as $element => $node)
        {
            $totalElement = count($xml->{$element});

            if (!isset($array[$element])) {
                $array[$element] = "";
            }

            // attributes
            if ($attributes = $node->attributes())
            {
                if (!count($node->children())){
                    $data['value'] = (string)$node;
                } else {
                    $data = array_merge($data, self::xml2Array($node, false));
                }
                foreach ($attributes as $attr => $value) {
                    $data[$attr] = (string)$value;
                }

                if ($totalElement > 1) {
                    $array[$element][] = $data;
                } else {
                    $array[$element] = $data;
                }
            // only a value
            } else {
                if ($totalElement > 1) {
                    $array[$element][] = self::xml2Array($node, false);
                } else {
                    $array[$element] = self::xml2Array($node, false);
                }
            }
        }

        if ($root) {
            return array($xml->getName() => $array);
        } else {
            return $array;
        }
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