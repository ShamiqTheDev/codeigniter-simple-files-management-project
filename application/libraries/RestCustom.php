<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Rest Curl Class
 *
 */
class RestCustom {

	public $response = '';          // Contains the cURL response for debug
	public $error_code;             // Error code returned as an int
	public $error_string;           // Error message returned as a string
	public $last_response;          // Returned after request (elapsed time, etc)
        
        /**
         * Simple Get Data via url-encoded
         */
        public function getData($location, $header, $data){
            
            // set up the curl resource
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            // execute the request
            $output = curl_exec($ch);
			
			$xml = simplexml_load_string($output);
			
			$output = $xml;
			
			$status = curl_getinfo($ch);
            //$output = json_decode($output);
            $output->httpCode = $status['http_code'];
            $output = json_encode($output);
            
            // output the profile information - includes the header
            //$this->response = $output;
            $result = json_decode($output);
            
            // close curl resource to free up system resources
            curl_close($ch);
            return $result;
            //return $this->response;
        }
        
        /**
        *  Example API call post
        *  Insert New Record in Database
        */
        public function post($location, $header, $data){
            
            // json encode data
            $data_string = json_encode($data); 
            //echo $data_string; exit;
            // set up the curl resource
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            //curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            
            // execute the request
            $output = curl_exec($ch);
            
            $status = curl_getinfo($ch);
            $output = json_decode($output);
            $output->httpCode = $status['http_code'];
            $output = json_encode($output);
            
            // output the profile information - includes the header
            $this->response = $output;
            $result = json_decode($output);
            
            // close curl resource to free up system resources
            curl_close($ch);
            
            // check config parameter if it's 1 then do log work
            $checkLog = config_item('customLogs');
            
            if($checkLog == 1){
                // create or add response to log file
                $time = date("Y-m-d h:i:s D", time());
                $baseDir = config_item('base_dir');
                $dirPath = $baseDir."logs";
                if(!is_dir($dirPath)){
                    mkdir($dirPath,0777);
                }
                $requestPath = $dirPath."/RequestLogs.txt";
                $responsePath = $dirPath."/ResponseLogs.txt";

                $msg = "\r\n-----------------------------------\r\n";
                $msg .= $time."\r\n";
                $msg .= "-----------------------------------\r\n";
                $msg .= $location."\r\n";
                $msg .= "-----------------------------------\r\n";
                $msg .= json_encode($header)."\r\n";
                $msg .= "-----------------------------------\r\n";
                $msg .= $data_string."\r\n";
                $msg .= "####################################\r\n";
                $msg .= "####################################\r\n";

                $f = (file_exists($requestPath))? fopen($requestPath, "a+") : fopen($requestPath, "w+");
                fwrite($f, $msg);
                fclose($f);
                chmod($requestPath, 0777);

                $resp = "\r\n-----------------------------------\r\n";
                $resp .= $time."\r\n";
                $resp .= "-----------------------------------\r\n";
                $resp .= $location."\r\n";
                $resp .= "-----------------------------------\r\n";
                $resp .= json_encode($header)."\r\n";
                $resp .= "-----------------------------------\r\n";
                $resp .= $data_string."\r\n";
                $resp .= "-----------------------------------\r\n";
                $resp .= $output."\r\n";
                $resp .= "####################################\r\n";
                $resp .= "####################################\r\n";

                $file = (file_exists($responsePath))? fopen($responsePath, "a+") : fopen($responsePath, "w+");
                fwrite($file, $resp);
                fclose($file);
                chmod($responsePath, 0777);
            }
            
            return $this->response;
        }
        
        /**
        *  Example API call get
        *  GET information from D.B
        */
        public function get($location, $header, $data){
            // set up the curl resource
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            // execute the request
            $output = curl_exec($ch);

            // output the profile information - includes the header
            $response = $output;

            // close curl resource to free up system resources
            curl_close($ch);
            return $response;
        }
        
        
        /**
        *  Example API call delete
        *  DELETE entry from Database
        */
        public function delete($location, $data){
            // set up the curl resource

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

            // execute the request
            $output = curl_exec($ch);

            // output the profile information - includes the header
            $response = $output;

            // close curl resource to free up system resources
            curl_close($ch);
            return $response;
        }
        
        
        /**
        *  Example API call PUT
        *  Update the profiles in a database
        */
        public function put($location, $data, $update){
            
            // make the POST fields
            $data_string = json_encode($update); 

            // initialize array
            $url = array();

            foreach ($data as $key => $value)
            {
                // make the url encoded query string
                $url[] = 'fields[]='.urlencode($key.'!='.$value);
            }

            $url = implode('&', $url);

            // set up the curl resources
            $ch = curl_init();

            echo ("$location?$url");

            //curl_setopt($ch, CURLOPT_URL, "$location&$url");
            curl_setopt($ch, CURLOPT_URL, $location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // note the PUT here

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HEADER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                'Content-Type: application/json',                                                                      'Content-Length: ' . strlen($data_string)                                                                       
            ));       

            // execute the request
            $output = curl_exec($ch);
            //print_r($output);
            // output the profile information - includes the header
            $response = $output;

            // close curl resource to free up system resources
            curl_close($ch);
            return $response;
        }
        
        public function set_defaults()
	{
            $this->response = '';
            $this->error_code = NULL;
            $this->error_string = '';
	}
       
}

/* End of file RestCurl.php */
/* Location: ./application/libraries/RestCurl.php */