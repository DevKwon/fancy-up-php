<?php
/**
@ 2014.4.1 
@ apmsoft@gmail.com
*/
class PushIOSMessage
{
	var $apn_url = '';
	var $devices = array();
	var $service_ssl_url = 'ssl://gateway.push.apple.com:2195';
	var $dev_ssl_url = 'ssl://gateway.sandbox.push.apple.com:2195';
	var $fp;
	
	function PushIOSMessage($apn_path){
		if($apn_path){
			$this->apn_url = $apn_path;
			
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apn_url);
				
			$this->fp = stream_socket_client($this->dev_ssl_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
			if (!$this->fp) {
				$this->error("Failed to connect $err $errstr\n");
				return;
			}
		}
	}
	
	function setDevices($deviceIds){	
		if(is_array($deviceIds)){
			$this->devices = $deviceIds;
		} else {
			$this->devices = array($deviceIds);
		}	
	}
	
	function send($title,$message,$view_type,$view_seq,$sync_time)
	{		
		if(!is_array($this->devices) || count($this->devices) == 0){
			$this->error("No devices set");
		}

		$count = count($this->devices);
		for ($i=0; $i<$count; $i++)	
		{
			$fields = array(
				'aps' 		=> array(
								'alert' => $title, 
								'badge' => 1, 
								'sound' => 'default'
							),			
				'message'	=> $message,
				'mode'		=> $view_type,
				'uid'		=> $view_seq,
				'sync_time'	=> $sync_time
			);

			$payload = json_encode($fields);

			$msg = chr(0).pack("n",32).pack('H*',$this->devices[$i]).pack("n",strlen($payload)).$payload;
			//echo "Send : ". $payload ."\n";
			fwrite($this->fp, $msg);
		}
		fclose($this->fp);
	}
	
	function error($msg){
		echo "IOS send notification failed with error:";
		echo "\t" . $msg;
		exit(1);
	}
}
?>