<?php
class UtilCache
{
    private $cache_data = array();
    private $cache_filename = '';
    
    #@ void
    # 데이타 스토리지 열기
    public function connect($cache_filename)
    {
        $this->cache_filename = $cache_filename.'.cache.php';
        try{
            $preferenceObj = new PreferenceInternalStorage($this->cache_filename,'r');
            $storage_data =$preferenceObj->readInternalStorage();
            if($storage_data){
                $this->cache_data = json_decode($storage_data,true);
            }
        }
        catch(Exception $e){
            //echo $e->getMessage();
        }
    }
    
    #@ return array
    public function get($cache_id){
        if(is_array($this->cache_data))
        {
            if($this->isCacheValue($cache_data)){
                return $this->cache_data[$cache_id];
            }
        }
    return false;
    }
    
    #@ void
    # 배열 끝에 등록
    public function set($cache_id, $data, $cache_time)
    {
        if($this->isCacheValue($cache_id) ===false){
            $this->cache_data[$cache_id] = $data;
            if(!$this->cache_data[$cache_id.'_time'] || $this->cache_data[$cache_id.'_time'] < time()){
                $this->cache_data[$cache_id.'_time'] = time()+$cache_time;
            }            
        }else{
            $this->cache_data[$cache_id] = $data;
            if(!$this->cache_data[$cache_id.'_time'] || $this->cache_data[$cache_id.'_time'] < time()){
                $this->cache_data[$cache_id.'_time'] = time()+$cache_time;
            }
        }
    }
    
    #@ void
    # 배열 삭제
    public function delete($cache_id)
    {
        if($this->isCacheValue($cache_id) !==false){
            unset($this->cache_data[$cache_id]);
        }
    }
    
    #@ return int, boolean
    # 배열에 존재하는지 체크
    public function isCacheValue($cache_id){
        if(isset($this->cache_data[$cache_id])){
            if($this->cache_data[$cache_id.'_time'] > time()){
                // echo 'cache : '.date('Y-m-d H:i:s',$this->cache_data[$cache_id.'_time']);
                // echo '->';
                // echo 'cache : '.date('Y-m-d H:i:s',time());
                return true;
            }
        }
    return false;
    }
    
    #@ void
    # 저장
    public function write(){
        if(is_array($this->cache_data)){
            if(count($this->cache_data)>0){
                $preferenceObj = new PreferenceInternalStorage($this->cache_filename,'w');
                $preferenceObj->writeInternalStorage(strval(json_encode($this->cache_data)));
            }
        }
    }
}
?>
