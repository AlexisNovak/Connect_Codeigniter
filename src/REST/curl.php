<?php 

include_once 'syraz_curl.php';

if(isset($_POST['request-url'])) {

    $url = $_POST['request-url'];
    $request_type = strtoupper($_POST['request-method']);
    $request_data = $_POST['request-data'];


    $curl = new Syraz_curl();
    $curl->set_url($url);
    $curl->set_request_type($request_type);
    $curl->set_header('Content-Type: application/json');

    if($request_type == 'POST' or $request_type == 'PUT') {

        //$request_data = json_decode($_POST['request-data']);
        $request_data = trim($request_data);
                                                            
        $curl->set_data($request_data);  
        $curl->set_header('Content-Length: ' . strlen($request_data));  
    }
                   
    $curl->set_option(CURLOPT_USERPWD, "sshrestha:password1");  
    $curl->set_option(CURLOPT_RETURNTRANSFER, true);  
    $curl->set_option(CURLOPT_SSLVERSION, 3); 
    $curl->set_option(CURLOPT_SSL_VERIFYPEER, false);  
    $curl->set_option(CURLOPT_SSL_VERIFYHOST, false);  

    $output = $curl->execute();

    if ($output === FALSE) {  
        $curl->get_info(); 
        $curl->get_error(); 
	} else {
        if((substr($output, 0, 1) == '{' or substr($output, 0, 1) == '[') and json_decode($output) != '') {
            echo '<pre>';
            print_r(json_decode($output));
            echo '</pre>';
        } else if($output == ''){
            echo 'Curl Success but nothing to output';
        } else {
            echo $output;
        }
	}

}