<?php
/*
$apiKey = "YOUR GOOGLE API KEY";
$devices = array('YOUR REGISTERED DEVICE ID');
$message = "The message to send";

$gcpm = new PushGCMMessage($apiKey);
$gcpm->setDevices($devices);
$response = $gcpm->send($title,$message,$params=array());
*/
class PushGCMMessage
{
	var $url = 'https://android.googleapis.com/gcm/send';
	var $serverApiKey = "";
	var $devices = array();
	
	function PushGCMMessage($apiKeyIn){
		$this->serverApiKey = $apiKeyIn;
	}

	function setDevices($deviceIds){	
		if(is_array($deviceIds)){
			$this->devices = $deviceIds;
		} else {
			$this->devices = array($deviceIds);
		}	
	}

	function send($title,$message,$view_type,$view_seq)
	{		
		if(!is_array($this->devices) || count($this->devices) == 0){
			$this->error("No devices set");
		}
		
		if(strlen($this->serverApiKey) < 8){
			$this->error("Server API Key not set");
		}
		
		$fields = array(
			'registration_ids'  => $this->devices,
			'data'              => array(
			"title"		=> $title,
			"message"	=> $message,
			"mode"		=> $view_type,
			"uid"		=> $view_seq
			),
		);
		
		$headers = array( 
			'Authorization: key=' . $this->serverApiKey,
			'Content-Type: application/json'
		);

		// Open connection
		$ch = curl_init();
		
		// Set the url, number of POST vars, POST data
		curl_setopt( $ch, CURLOPT_URL, $this->url );		
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );		
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
		
		// Execute post
		$result = curl_exec($ch);
		/*
		$fop = fopen("/home/nameq/www/log/result_log.txt", "a");
		fwrite($fop, $result); 
		fwrite($fop, "\r\n ----------------------------------------------------------------------------------------------- \r\n"); 
		fclose($fop);
		*/

		// Close connection
		curl_close($ch);
		
	return $result;
	}
	
	function error($msg){
		echo "Android send notification failed with error:";
		echo "\t" . $msg;
		exit(1);
	}
}
	
?>
