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
			if(!$isChceker->isNull())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}
	}
    
    # 아이디체크
    public function chkUserid($filed,$value,$required){
        $isChceker = new ReqStrChecker($value);
        if($required){
            if(!$isChceker->isNull())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
        }

        if($value){
            if(!$isChceker->isSpace())
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_space']);
            if(!$isChceker->isStringLength(4,14)) 
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_userid_lenth']);
            if($isChceker->isKorean()) 
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_korean']);
            if(!$isChceker->isEtcString('')) 
                self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_symbole']);
        }
    }
	
	# 비밀번호
	public function chkPasswd($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if(!$isChceker->isNull())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_space']);
			if(!$isChceker->isStringLength(4,30)) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_passwd_lenth']);
			if($isChceker->isKorean()) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_korean']);
			if(!$isChceker->isEtcString('')) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_symbole']);
		}
	}

	# 이름
	public function chkName($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if(!$isChceker->isNull()) self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace()) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_space']);
			if(!$isChceker->isEtcString(''))
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_symbole']);
		}
	}

	# 전화번호
	public function chkPhone($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if(!$isChceker->isNull()) self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace()) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_space']);
			if(!$isChceker->isNumber())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_phone_symbole']);
			//if(!$isChceker->isSameRepeatString(5))
			//	self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_number_repeat']);
			//if(!$isChceker->isEtcString('-'))
				//self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_phone_symbole']);
		}
	}
	
	# 숫자만
	public function chkNumber($filed,$value,$required){
		$isChceker = new ReqStrChecker($value);
		if($required){
			if(!$isChceker->isNull()) self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace()) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_space']);
			if(!$isChceker->isNumber())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_number']);
		}
	}
	
	# 이메일 sed_-23@apmsoftax.com
	public function chkEmail($filed,$value,$required){
		$value =filter_var($value,FILTER_SANITIZE_EMAIL);
		$isChceker = new ReqStrChecker($value);
		if($required){
			if(!$isChceker->isNull())
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}

		if($value){
			if(!$isChceker->isSpace()) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_space']);
			if($isChceker->isKorean()) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_korean']);
			if(!filter_var($value, FILTER_VALIDATE_EMAIL)) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_formality']);
			if(!$isChceker->isEtcString('@,-,_')) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_email_symbole']);
		}
	}
	
	# 링크주소
	public function chkLinkurl($filed,$value,$required){
		$value =filter_var($value,FILTER_SANITIZE_URL);
		$isChceker = new ReqStrChecker($value);
		if($required){
			if(!$isChceker->isNull()) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_null']);
		}
		
		if($value){
			if(!filter_var($value, FILTER_VALIDATE_URL)) 
				self::error_report($filed, $this->strings[$filed].' '.$this->strings['err_formality']);
		}
	}

	public function error_report($filed, $msg){
		Out::prints_json(array('result'	=>'false','fieldname'=>$filed,'msg'=>$msg));
	}
}
?>