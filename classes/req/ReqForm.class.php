<?php
# 폼체크
class ReqForm
{
	private $strings = array();

	#@ void
	#언어 목록
	public function __construct(&$strings){
		$this->strings =&$strings;
	}

	# 널값만 체크
	public function chkNull($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if($isChceker->isNull()) {
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		    }
        }
	}

    # 아이디체크
    public function chkUserid($filed,$value,$required){
        $isChceker = new ReqStrChecker($value);
        if($required){
            if($isChceker->isNull())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
        }

        if($value){
            if(!$isChceker->isSpace())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_spaces']);
            if(!$isChceker->isStringLength(4,14))
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_userid_length']);
            if($isChceker->isKorean())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_korean']);
            if(!$isChceker->isEtcString(''))
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_symbol']);
        }
    }

	# 비밀번호
	public function chkPasswd($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if($isChceker->isNull())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_spaces']);
			if(!$isChceker->isStringLength(4,30))
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_password_length']);
			if($isChceker->isKorean())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_korean']);
			if(!$isChceker->isEtcString(''))
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_symbol']);
		}
	}

	# 이름
	public function chkName($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if($isChceker->isNull()) self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_spaces']);
			if(!$isChceker->isEtcString(''))
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_symbol']);
		}
	}

	# 전화번호
	public function chkPhone($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if($isChceker->isNull()) self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_spaces']);
			if(!$isChceker->isNumber())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_phone_symbol']);
			//if(!$isChceker->isSameRepeatString(5))
			//	self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_number_repeat']);
			//if(!$isChceker->isEtcString('-'))
				//self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_phone_symbol']);
		}
	}

	# 숫자만
	public function chkNumber($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if($isChceker->isNull()) self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_spaces']);
			if(!$isChceker->isNumber())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_number']);
		}
	}

	# 이메일 sed_-23@apmsoftax.com
	public function chkEmail($filed,$value,$required){
		$value =filter_var($value,FILTER_SANITIZE_EMAIL);
		$isChceker = new ReqStrChecker($value);
		if($required){
			if($isChceker->isNull())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_spaces']);
			if($isChceker->isKorean())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_korean']);
			if(!filter_var($value, FILTER_VALIDATE_EMAIL))
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_formality']);
			if(!$isChceker->isEtcString('@,-,_'))
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_email_symbol']);
		}
	}

	# 링크주소
	public function chkLinkurl($filed,$value,$required){
		$value =filter_var($value,FILTER_SANITIZE_URL);
		$isChceker = new ReqStrChecker($value);
		if($required){
			if($isChceker->isNull())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!filter_var($value, FILTER_VALIDATE_URL))
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_formality']);
		}
	}

    # 날짜
    public function chkDateFormat($filed,$value,$required){
        $isChceker = new ReqStrChecker($value);
        if($required){
            if($isChceker->isNull()) self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
        }

        if($value){
            if(!$isChceker->isSpace())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_spaces']);
            if($isChceker->isKorean())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_korean']);
            if(!$isChceker->isEtcString('-'))
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_date_symbol']);
            if(!$isChceker->chkDate())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_date_symbol']);
        }
    }

    #@ void
    # 기간체크
    # $filed_args = array('sdate','edate')
    # $value_args= array($_REQUEST['sdate'],$_REQUEST'edate'])
    # $required = true | false
    public function chkDatePeriod($field_args,$value_args,$required){

        // 배열인지 체크
        if(!is_array($field_args) || !is_array($value_args)){
            self::error_report($filed, $this->strings['err_date_period_array']);
        }

        if($required)
        {
            // 데이터 형식 체크
            foreach($field_args as $index => $field){
                self::chkDateFormat($field,$value_args[$index],$required);
            }

            // 기간체크
            $isChceker = new ReqStrChecker(implode('/', $value_args));
            if(!$isChceker->chkDatePeriod()){
                self::error_report($field_args[0], $this->strings['err_date_period']);
            }
       }
    }

	public function error_report($filed, $msg){
		Out::prints_json(array('result'	=>'false','fieldname'=>$filed,'msg'=>$msg));
	}
}
?>