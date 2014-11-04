<?php
/** ======================================================
| @Author   : 김종관 | 010-4023-7046
| @Email    : apmsoft@gmail.com
| @HomePage : http://www.apmsoftax.com
| @Editor   : Eclipse(default)
| @vsesion  : 0.5
----------------------------------------------------------*/
class StringRandomSpam extends StringRandom
{
    /**
     * [$filter_spam_str description]
     * @var filter_spam_str     : 사용할 랜덤 영문숫자 조합 문자
     * @var input_mix_str       : 사용자에게 보이는 문자
     * @var filter_input_args   : 스팸필터링 문자롤 사용할 배열
     */
    private $filter_spam_str;
    private $input_mix_str = '';
    private $filter_input_args = array(
                3=>array(1=>'0//3456/89', 2=>'/123//6789'),
                4=>array(1=>'/12//5/789', 2=>'/12//56/89'),
                5=>array(1=>'01////6/89', 2=>'/123///7/9'),
                6=>array(1=>'0/234/////', 2=>'01//4///8/'),
                7=>array(1=>'01///5////', 2=>'///3///7/9'),
                8=>array(1=>'////4///7//',2=>'0/////6///'),
                9=>array(1=>'/1////////', 2=>'//2///////')
            );

    # html span style
    private $html_spantag_cssstyle = '';

    #@ void
    public function __construct(){
        $this->filter_spam_str = parent::arrayRand(parent::numberRand(3,6));
        self::setInputSpamMixString();
        self::setCssStyle(array('font-size:16pt', 'font-weight:bold', 'color:red'));
    }

    #@ return String
    public function __get($propertyName){
        if(property_exists(__CLASS__,$propertyName)){
            $result = $this->{$propertyName};
            if($propertyName=='input_mix_str'){
                $result = str_replace('{{css_style}}', $this->html_spantag_cssstyle, $result);
            }
            return $result;
        }
    return false;
    }

    #@ void
    #정해진 값 사이로 다른문자를 정해진 길이 만큼 끼워넣기
    public function setInputSpamMixString()
    {
        $lennumber = strlen($this->filter_spam_str);
        $ranv = parent::numberRand(1,2);
        $mixnum = $this->filter_input_args[$lennumber][$ranv];

        $x=0;
        for($i=0; $i<10; $i++)
        {
            $tmpstr = substr($mixnum,$i,1);
            if($tmpstr != '/'){
                $this->input_mix_str.= $i;
            }else{
                $this->input_mix_str.= '<span style="{{css_style}}">'.substr($this->filter_spam_str,$x,1).'</span>';
                $x++;
            }
        }
    }

    #@ void
    # tags  : array ('font-size:16pt', 'font-weight:bold', 'color:red');
    # 사용자가 입력을 해야할 문자에 하이라이트 및 눈에 띄도록 하기 윈한 HTML 태그삽입
    public function setCssStyle($css_style){
        if(is_array($css_style)){
            $this->html_spantag_cssstyle = implode(';', $css_style);
        }
    }
}
?>