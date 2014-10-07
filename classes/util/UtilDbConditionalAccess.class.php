<?php
/** ======================================================
| @Author   : 김종관 | 010-4023-7046
| @Email    : apmsoft@gmail.com
| @HomePage : http://www.apmsoftax.com
| @Editor   : Eclipse(default)
| @version : 1.0.140926
 * 디비 접근에 필요한 조건 WHERE 문 만드는 유틸
----------------------------------------------------------*/
class UtilDbConditionalAccess
{
    private $where = '';
    
    public function __construct($where='')
    {
        if($where !=''){
            $this->where = $where;
        }
    }
    
    #@ void
    public function setAND($field, $q){
        if($q==''){ return; }
        
        $wh = sprintf("%s = '%s'", $field, $q);
        $this->where.= ($this->where!='') ? ' AND '.$wh : $wh;
    }
    
    #@ void
    public function setNegativeAND($field, $q){
        if($q==''){ return; }
        
        $wh = sprintf("`%s` != '%s'", $field, $q);
        $this->where.= ($this->where!='') ? ' AND '.$wh : $wh;
    }
    
    #@ void
    public function setOR($field, $q){
        if($q==''){ return; }
        
        $wh = sprintf("%s = '%s'", $field, $q);
        $this->where.= ($this->where!='') ? ' OR '.$wh : $wh;
    }
    
    #@ void
    public function setNegativeOR($field, $q){
        if($q==''){ return; }
        
        $wh = sprintf("%s != '%s'", $field, $q);
        $this->where.= ($this->where!='') ? ' OR '.$wh : $wh;
    }
    
    # 프라퍼티 값 리턴받기
    public function __get($propertyName){
        if(property_exists(__CLASS__,$propertyName)){
            return $this->{$propertyName};
        }
    }
    
    # @void
    # =, != 외
    public function __set($propertyName, $wh){
        if(property_exists(__CLASS__,$propertyName)){
            $this->{$propertyName} .= ($this->where!='') ? ' AND '.$wh : $wh;
        }
    }
}
?>