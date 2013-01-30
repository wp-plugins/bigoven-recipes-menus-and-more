<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/


if ( !class_exists('BOAPIClient') ):

class BOAPIClient {
	var $last_headers = '';
	
	function init($conf) {

		$this->conf = $conf;
		
	}
		
	function apiCall($url){
		$this->last_headers = '';
		
		$result = array(
			'broken' => false,
		);
		$log = '';
		
		//Get the BO configuration. It's used below to set the right timeout values and such.
		$conf = & BO_get_configuration();
		
		// Init curl...
	 	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // User Agent...
        $ua = 'BigOven WordPress/1.0';
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        
        // Set the proxy configuration. The user can provide this in wp-config.php 
        if (defined('WP_PROXY_HOST')) {
			curl_setopt($ch, CURLOPT_PROXY, WP_PROXY_HOST);
		}
		if (defined('WP_PROXY_PORT')) { 
			curl_setopt($ch, CURLOPT_PROXYPORT, WP_PROXY_PORT);
		}
		if (defined('WP_PROXY_USERNAME')){
			$auth = WP_PROXY_USERNAME;
			if (defined('WP_PROXY_PASSWORD')){
				$auth .= ':' . WP_PROXY_PASSWORD;
			}
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $auth);
		}

		// Make CURL return a valid result even if it gets a 404 or other error.
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
		        
		// It is JSON we ask for...
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    
        // Register a callback function which will process the HTTP header(s).
		// It can be called multiple times if the remote server performs a redirect. 
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(&$this,'read_header'));

		// Perform the HTTP request
        $data = curl_exec($ch);
        
        // Get result information...
		$info = curl_getinfo($ch);
		
		//Store the results
        $result['http_code'] 		= intval( $info['http_code'] );
        $result['final_url'] 		= $info['url'];
        $result['request_duration'] = $info['total_time'];
        $result['redirect_count'] 	= $info['redirect_count'];
        $result['data'] 			= $data;

        curl_close($ch);
       
        //Build the log from HTTP code and headers.
        $log .= '=== ';
        if ( $result['http_code'] ){
			$log .= sprintf( __('HTTP code : %d', 'bigoven'), $result['http_code']);
		} else {
			$log .= __('(No response)', 'bigoven');
		}
		$log .= " ===\n\n";
        $log .= $this->last_headers;
        
        $result['log'] = $log;
        
        //The hash should contain info about all pieces of data that pertain to determining if the 
		//link is working.  
        $result['result_hash'] = implode('|', array(
			$result['http_code'],
			$result['broken']?'broken':'0', 
			//$result['timeout']?'timeout':'0',
			md5($result['final_url']),
		));
        
        return $result;
	}
	
	function read_header($ch, $header){
		$this->last_headers .= $header;
		return strlen($header);
	}

}

endif;

?>