<?php
/** ======================================================
| @Author   : 김종관 | 010-4023-7046
| @Email    : apmsoft@gmail.com
| @HomePage : http://www.apmsoftax.com
| @Editor   : SUBLIME
| @UPDATE   : 0.1
----------------------------------------------------------*/
class XmlSimpleXMLElementPlus extends SimpleXMLElement
{
    /**
     * @void
     * CData 추가
     * @param [type] $cdata_text [description]
     */
    public function addCData($elemnetName, $contents)
    {
        if(selft::isNullChild($elemnetName)){
            $this->addChild($elemnetName);
        }

        $this->{$elemnetName} = '';
        $node = dom_import_simplexml($this->{$elemnetName});
        $no   = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($contents));
    }

    /**
     * @void
     * addChild
     * @param [type] $elemnetName [description]
     * @param [type] $contents    [description]
     */
    public function addChildPlus($elemnetName, $contents){
        if(selft::isNullChild($elemnetName)){
            $this->addChild($elemnetName);
        }

        $this->{$elemnetName} = $contents;
    }

    /**
     * @void
     * removeChild
     * @param  [type] $elemnetName [description]
     * @return [type]              [description]
     */
    public function removeChildPlus($elemnetName){
        if(!selft::isNullChild($elemnetName)){
            unset($this->{$elemnetName}[0]);
        }
    }

    /**
     * @return boolean
     * child Element Node 가 있는지 체크
     * @param  [type]  $elemnetName [description]
     * @return boolean              [description]
     */
    public function isNullChild($elemnetName){
        if(is_null($this->{$elemnetName}[0]))
            return true;
    return false;
    }
}
?>