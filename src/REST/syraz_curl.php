<?php 

class Syraz_curl {
	private $_url = '';
	private $_http_headers = array();
	private $_curl_options = NULL;
	private $_curl_info = NULL;
	private $_error = '';

	public function __construct() {
		$this->_curl_options = new stdClass();
		$this->_curl_options->{CURLOPT_CUSTOMREQUEST} = 'GET';
		$this->_curl_options->{CURLOPT_RETURNTRANSFER} = true;
	}

	public function initialize($curl_options = array()) {
		$this->_curl_options = new stdClass();
		foreach ($curl_options as $key => $value) {
			$this->_curl_options->$key = $value;
		}
	}

	public function reset() {
		$this->_curl_options = new stdClass();
	}

	public function set_option($name, $value) {
		$this->_curl_options->$name = $value;
	}

	public function set_header($header) {
		$this->_http_headers[] = $header;
	}

	public function set_url($url) {
		$this->_curl_options->{CURLOPT_URL} = $url;
	}

	public function set_request_type($request_type) {
		$this->_curl_options->{CURLOPT_CUSTOMREQUEST} = $request_type;
	}

	public function set_data($request_data) {
		$this->_curl_options->{CURLOPT_POSTFIELDS} = $request_data;
	}

	public function execute() {
		$ch = curl_init();

		$this->_curl_options->{CURLOPT_HTTPHEADER} = $this->_http_headers;

		foreach ($this->_curl_options as $key => $value) {
		    curl_setopt($ch, $key, $value); 
		}
		    
		$output = curl_exec($ch);

		$this->_curl_info = curl_getinfo($ch);

	    if ($output === FALSE) {  
		    $this->_error = array(
		    	'error_no' => curl_errno($ch),
		    	'error_message' => curl_error($ch)
		    );
		} 

	    curl_close($ch);

	    return $output;
	}

	public function get_info() {
		echo '<pre>';
		print_r($this->_curl_info);  
		echo '</pre><hr/>';
	}

	public function get_error() {
		echo '<pre>';
		print_r($this->_error);  
		echo '</pre><hr/>';
	}
	
	
}