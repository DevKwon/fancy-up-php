<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-16
----------------------------------------------------------*/

# parnet : DBSqliteResult
# purpose : sqlite 함수를 활용해 확장한다
class DbSqlite extends DbSqliteResult implements DbSwitch,ArrayAccess
{
	private $host,$dbname;
	private $handle;
	private $affected_rows;	# 저장 레코드 갯수
	private $changes_rows;	# 수정된 레코드 갯수
	private $params = array();
	
	# dsn : 파일경로:파일명
    public function __construct($dsn){
        $dsn_args = explode(':',$dsn);

        $this->handle = sqlite_open($dsn_args[0].'/'.$dsn_args[1]);
        if($errno_num = sqlite_last_error($this->handle)){
        	throw new ErrorException(sqlite_error_string($errno_num),$errno_num);
        }

		# 문자셋
		#$chrset = parent::character_set_name();
		#if(strcmp($chrset,'utf8')) parent::set_charset(str_replace('-','',_CHRSET_));

		$this->host		= $dsn_args[0];
		$this->dbname	= $dsn_args[1];
    }
    
	#@ interface : ArrayAccess
	# 사용법 : $obj["two"] = "A value";
    public function offsetSet($offset, $value) {
        $this->params[$offset] = sqlite_escape_string($value);
    }
    
    #@ interface : ArrayAccess
    # 사용법 : isset($obj["two"]); -> bool(true)
	public function offsetExists($offset) {
        return isset($this->params[$offset]);
    }
    
    #@ interface : ArrayAccess
    # 사용법 : unset($obj["two"]); -> bool(false)
	public function offsetUnset($offset) {
        unset($this->params[$offset]);
    }
    
    #@ interface : ArrayAccess
    # 사용법 : $obj["two"]; -> string(7) "A value"
	public function offsetGet($offset) {
        return isset($this->params[$offset]) ? $this->params[$offset] : null;
    }
	
	# @ interface : DBSwitch
	// SELECT ? FROM kc_bbs_table WHERE id='?' AND del_date !='1' ORDER BY fid ASC LIMIT ?,?
	// query_bind_params($bbs_qry,'ssdd',array('*',$bbs_id,0,10));
	public function query_bind_params($query,$bind,$args=array())
	{
		if(strpos($query,'?') !==false)
		{
			preg_match_all("/([?])+/s",$query,$matches);
			if(is_array($matches))
			{
				# 바인드 갯수 여부 확인
				$len =strlen($bind);
				if($len != count($matches[0]))
					throw new ErrorException('Parameter mismatch');

				# 쿼리문장 배열로나눔
				$qry_args =preg_split('/[?]/s', $query);

				# 데이타 형식 체크 및 쿼리문 만들기
				$result='';
				for($i=0; $i<$len; $i++){
					if($args[$i]){
						$bindtype=substr($bind,$i,1);
						switch($bindtype){
							case 's': if(!is_string($args[$i])) strval($args[$i]); break;
							case 'd': if(!is_int($args[$i])) intval($args[$i]); break;
							case 'u': if(!is_double($args[$i])) doubleval($args[$i]); break;
							case 'f': if(!is_float($args[$i])) floatval($args[$i]); break;
						}
					}
					$result.=$qry_args[$i].$args[$i];
				}
			return $result;
			}
		}else{
			return $query;
		}
	}

    # @ interface : DBSwitch
	public function query($query){
		$result = sqlite_query($this->handle,$query);
        if( !$result ){
        	$errno_num = sqlite_last_error($this->handle);
        	throw new ErrorException(sqlite_error_string($errno_num),$errno_num);
        }
        $this->num_rows = sqlite_num_rows($result);
        $this->resultHandle = $result;
    return $this;
    }

	# @ interface : DBSwitch
	# args = array(key => value)
	# args['name'] = 1, args['age'] = 2;
	public function insert($table){
		$fieldk = '';
		$datav	= '';
		
		if(count($this->params)<1) return false;		
		foreach($this->params as $k => $v){
			$fieldk .= sprintf("%s,",$k);
			$datav	.= sprintf("'%s',",$v);			
		}
		$fieldk	= substr($fieldk,0,-1);
		$datav	= substr($datav,0,-1);
		$this->params = array(); #변수값 초기화
		
		$query	= sprintf("INSERT INTO %s (%s) VALUES (%s)",$table,$fieldk,$datav);
		$this->exec($query);
	}
    
	# @ interface : DBSwitch
	public function update($table,$where){
		$fieldkv = '';
		
		if(count($this->params)<1) return false;		
		foreach($this->params as $k => $v){
			$fieldkv .= sprintf("%s='%s',",$k,$v);		
		}
		$fieldkv = substr($fieldkv,0,-1);
		$this->params = array(); #변수값 초기화

		$query	= sprintf("UPDATE %s SET %s WHERE %s",$table,$fieldkv,$where);
		$this->exec($query);
	}

	# @ interface : DBSwitch
    public function delete($table,$where){
    	$query = sprintf("DELETE FROM %s WHERE %s",$table,$where);
    	$this->exec($query);
    }
    
	# 디비 선택
    public function select_db($dbname){
    	$this->handle = sqlite_factory($this->host.'/'.$dbname);
    	
    	if($errno_num = sqlite_last_error($this->handle)){
    		throw new ErrorException(sqlite_error_string($errno_num),$errno_num);
        }
        
        $this->dbname= $dbname;
    }

	# 프라퍼티 값 가져오기
	public function __get($property){
		return $this->{$property};
	}
	
    # insert,update,delete 에 사용
	public function exec($query){
    	$result = sqlite_exec($this->handle,$query,$error);
    	if(!$result){
    		throw new ErrorException("Error in query: '.$error.'");
    	}else{
    		$this->changes_rows = sqlite_changes($this->handle);
    	}
    }
    
    # 저장한 마지막 primary_key 값
    public function insert_id(){
    	return sqlite_last_insert_rowid($this->handle);
    }
    
    # 버전정보
    public function server_info(){
    	return sqlite_libversion();
    }
    
    # 문자셋 정보
	public function character_set_name(){
		return sqlite_libencoding();
	}
    
    # db close
    public function __destruct(){
    	sqlite_close($this->handle);
    }
}
?>