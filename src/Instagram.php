<?php
<<<<<<< HEAD
namespace Haridarshan\Instagram;

use Haridarshan\Instagram\InstagramException;
use Haridarshan\Instagram\InstagramOAuthException;
=======
namespace haridarshan\Instagram;

use haridarshan\Instagram\InstagramException;
use haridarshan\Instagram\InstagramOAuthException;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec

/**
 * Instagram API class
 *
 * API Documentation: http://instagram.com/developer/
 * Class Documentation: https://github.com/haridarshan/Instagram-php
 *
 * @author Haridarshan Gorana
 * @since May 09, 2016
 * @copyright Haridarshan Gorana
 * @version 1.0
 * @license: GNU GPL v3 License
 */
class Instagram{
	/*
	 * API End Point
	 */  
	const API_HOST = 'https://api.instagram.com/v1';
	
	/*
	 * API Core End Point
	 */ 
	const API_CORE = 'https://api.instagram.com';
	
	/*
	 * Client Id
	 * @var: string
	 */
<<<<<<< HEAD
	private $client_id;
=======
	private $_client_id;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	
	/*
	 * Client Secret
	 * @var: string
	 */
<<<<<<< HEAD
	private $client_secret;
=======
	private $_client_secret;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	
	/*
	 * Instagram Callback url
	 * @var: string
	 */
<<<<<<< HEAD
	private $callback_url;
=======
	private $_callback_url;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	
	/*
	 * Oauth Access Token
	 * @var: string
	 */
<<<<<<< HEAD
	private $access_token;
=======
	private $_access_token;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	
	/*
	 * Instagram Available Scope
	 * @var: array of strings
	 */
<<<<<<< HEAD
	private $scopes = array();
	
	/*
	 * Enable secure request
	 * @var: boolean
	 */ 
	private $secure = true;
=======
	private $_scopes = array();
	
	/*
	 * Header signed
	 * @var: boolean
	 */ 
	private $_signed = true;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	
	/*
	 * Curl timeout
	 * @var: integer|decimal|long
	 */
<<<<<<< HEAD
	private $timeout = 90;
=======
	private $_timeout = 90;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	
	/*
	 * Curl Connect timeout
	 * @var: integer|decimal|long
	 */
<<<<<<< HEAD
	private $connect_timeout = 20;
=======
	private $_connect_timeout = 20;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	
	/*
	 * Remaining Rate Limit
	 * Sandbox = 500
	 * Live = 5000
	 */
<<<<<<< HEAD
	private $x_rate_limit_remaining = 500;
=======
	private $_x_rate_limit_remaining = 500;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
		
	/*
	 * Default Constructor 
	 * @param: array|object|string
	 * I/p: Instagram Configuration Data
	 * @return: void
	 */
	public function __construct($config){		
<<<<<<< HEAD
		if (is_object($config)) {
			$this->setClientId($config->ClientId);
			$this->setClientSecret($config->ClientSecret);
			$this->setCallbackUrl($config->Callback);			
		} elseif (is_array($config)) {			
			$this->setClientId($config['ClientId']);
			$this->setClientSecret($config['ClientSecret']);
			$this->setCallbackUrl($config['Callback']);	
		} elseif (is_string($config)) {
			$this->setClientId($config);
		} else {
=======
		if( is_object($config) ){
			$this->setClientId($config->ClientId);
			$this->setClientSecret($config->ClientSecret);
			$this->setCallbackUrl($config->Callback);			
		}elseif( is_array($config) ){			
			$this->setClientId($config['ClientId']);
			$this->setClientSecret($config['ClientSecret']);
			$this->setCallbackUrl($config['Callback']);	
		}elseif( is_string($config) ){
			$this->setClientId($config);
		}else{
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
			throw new InstagramException('Invalid Instagram Configuration data');
			exit();
		}
	}
	
	 /**
     * Make URLs for user browser navigation.
     *
     * @param string $path
     * @param array  $parameters
     *
     * @return string
     */
	public function getUrl($path, array $parameters){
		
<<<<<<< HEAD
		if (isset($parameters['scope'])) {
			$this->scopes = $parameters['scope']; 
		}
		
		if (is_array($parameters)) {
			$query = 'client_id='. $this->getClientId() .'&redirect_uri='. urlencode($this->getCallbackUrl()) .'&response_type=code';
			
			if (isset($this->scopes)) {
=======
		if( isset($parameters['scope']) ){
			$this->_scopes = $parameters['scope']; 
		}
		
		if( is_array($parameters) ){
			$query = 'client_id='. $this->getClientId() .'&redirect_uri='. urlencode($this->getCallbackUrl()) .'&response_type=code';
			
			if( isset($this->_scopes) ){
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
				$scope = urlencode(str_replace(",", " ", implode(",", $parameters['scope'])));

				$query .= "&scope=$scope";
			}
			
			return sprintf('%s/%s?%s', self::API_CORE, $path, $query);
		}
		
		throw new InstagramOAuthException("Invalid scope permissions used.");
	}
	
	/*
	 * Get the Oauth Access Token of a user from callback code
	 * 
	 * @param string $path - OAuth Access Token Path
	 * @param string $code - Oauth2 Code returned with callback url after successfull login
	 * @param boolean $token - true will return only access token
	 */
	public function getToken($path, $code, $token = false){
		$options = array(
			"grant_type" => "authorization_code",
			"client_id" => $this->getClientId(),
			"client_secret" => $this->getClientSecret(),
			"redirect_uri" => $this->getCallbackUrl(),
			"code" => $code
		);
		
		$apihost = self::API_CORE.'/'.$path;
		
<<<<<<< HEAD
		$result = $this->curlCall($apihost, $options, 'POST');
		
		if (isset($result->code)) {
=======
		$result = $this->__CurlCall($apihost, $options, 'POST');
		
		if( isset($result->code) ){
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
			throw new InstagramOAuthException("return status code: ". $result->code ." type: ". $result->error_type ." message: ". $result->error_message );
		}
				
		$this->setAccessToken($result);
		
		return !$token ? $result : $result->access_token;
	}
	
	/*
	 * Secure API Request by using endpoint, paramters and API secret
	 * copy from Instagram API Documentation: https://www.instagram.com/developer/secure-api-requests/
	 * 
	 * @param string $endpoint
	 * @param string $params
	 * @param string $secret
	 *
	 * return string (Signature)
	 */
<<<<<<< HEAD
	protected function secureRequest($endpoint, $auth, $params){	
		if (!is_array($params)) {
			$params = array();	
		}
		
		if ($auth) {
=======
	private function __secureRequest($endpoint, $auth, $params){	
		if( !is_array($params) ){
			$params = array();	
		}
		
		if( $auth ){
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
			list($key, $value) = explode("=", substr($auth, 1), 2);
			$params[$key] = $value;
		}
		
		$signature = $endpoint;
		ksort($params);
		
		foreach($params as $key => $value){
			$signature .= "|$key=$value";	
		}
					
		return hash_hmac('sha256', $signature, $this->getClientSecret(), false);
	}
	
	/*
	 * Request method to make api calls either secure or insecure
	 *
	 * @param string $api - API End Point to call
	 * @param boolean $authentication - false api doesn't require authentication | true api requires authentication 
	 * @param array|object|string|null $params 
	 * @param string $method - GET|POST
	 *
	 */
<<<<<<< HEAD
	protected function requestCall($api, $authentication = false, $params = null, $method = 'GET'){
			
		// If api call doesn't requires authentication
		if (!$authentication) {
			$authentication_method = '?client_id=' . $this->getClientId();
		} else {
			if (!isset($this->access_token)) {
=======
	protected function __request($api, $authentication = false, $params = null, $method = 'GET'){
			
		// If api call doesn't requires authentication
		if( !$authentication ){
			$authentication_method = '?client_id=' . $this->getClientId();
		}else{
			if( !isset($this->_access_token) ){
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
				throw new InstagramException("$api - api requires an authenticated users access token.");
				exit();
			}
			
			$authentication_method = '?access_token=' . $this->getAccessToken();
		}
		// This portion needs modification
		$param = null;
		
		if (isset($params) and is_array($params) ) {
			$param = '&' . http_build_query($params);	
		}
		
		$apihost = self::API_HOST .'/'. $api . $authentication_method . (('GET' === $method) ? $param : null);
				
<<<<<<< HEAD
		if ($this->secure) {
            $apihost .= (strstr($apihost, '?') ? '&' : '?') . 'sig=' . $this->secureRequest($api, $authentication_method, $params);
        }
		
		$json = $this->curlCall($apihost, $param, 'GET');
=======
		if ($this->_signed) {
            $apihost .= (strstr($apihost, '?') ? '&' : '?') . 'sig=' . $this->__secureRequest($api, $authentication_method, $params);
        }
		
		$json = $this->__CurlCall($apihost, $param, 'GET');
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
		
		return $json;
	}
	
	 /**
     * The OAuth call Method.
     *
	 * @param string $host - OAuth host
     * @param array $data The post API data
	 * @param string $method - GET|POST|DELETE
     *
     * @return mixed
     *
     * @throws \haridarshan\Instagram\InstagramException
     */
<<<<<<< HEAD
	private function curlCall($host, $data, $method = 'GET', $headers = true){
=======
	private function __CurlCall($host, $data, $method = 'GET', $headers = true){
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $host);		
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->getConnectTimeout());		
		
<<<<<<< HEAD
		if ($headers == true) {
			curl_setopt($ch, CURLOPT_HEADER, true);	
		}
		
		switch ($method) {
			case 'POST':
				if (is_array($data)) {
					curl_setopt($ch, CURLOPT_POST, count($data));
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
				} else {
=======
		if( $headers == true ){
			curl_setopt($ch, CURLOPT_HEADER, true);	
		}
		
		switch($method){
			case 'POST':
				if( is_array($data) ){
					curl_setopt($ch, CURLOPT_POST, count($data));
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
				}else{
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
					curl_setopt($ch, CURLOPT_POST, count($data));
					curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($data, '&'));
				}
				break;
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}
		
        $json = curl_exec($ch);
		
<<<<<<< HEAD
		if ($headers == true) {
=======
		if( $headers == true ){
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
			// split header from response data
			// and assign each to a variable
			list($headercontent, $json) = explode("\r\n\r\n", $json, 2);

			// convert header content into an array
<<<<<<< HEAD
			$getheaders = $this->processHeaders($headercontent);

			// get the 'X-Ratelimit-Remaining' header value
			$this->x_rate_limit_remaining = $headers['X-Ratelimit-Remaining'];
=======
			$getheaders = $this->_processHeaders($headercontent);

			// get the 'X-Ratelimit-Remaining' header value
			$this->_x_rate_limit_remaining = $headers['X-Ratelimit-Remaining'];
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
		}
		
		if (!$json) {
			throw new InstagramException('cURL error: ' . curl_error($ch));
			exit();
		}
		curl_close($ch);
		return json_decode($json);
		
	}
	
	/* 
	 * Method to make api requests
	 *
	 * return mixed
	 */
	public function request($path, $params = null){
<<<<<<< HEAD
		if ($this->x_rate_limit_remaining < 1) {
			throw new InstagramException("You have reached Instagram API Rate Limit");
			exit();
		} else {
			if (isset($params['access_token']) and !isset($this->access_token)) {
				$this->setAccessToken($params['access_token']);	
			}
			
			return $this->requestCall($path, true, $params);			
=======
		if( $this->_x_rate_limit_remaining < 1 ){
			throw new InstagramException("You have reached Instagram API Rate Limit");
			exit();
		}else{
			if( isset($params['access_token']) and !isset($this->_access_token) ){
				$this->setAccessToken($params['access_token']);	
			}
			
			return $this->__request($path, true, $params);			
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
		}
	}
	
	
	/*
	 * Setter: Client Id
	 * @param: string $clientId
	 * @return: void
	 */
	public function setClientId($clientId){
<<<<<<< HEAD
		$this->client_id = $clientId;	
=======
		$this->_client_id = $clientId;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Getter: Client Id
	 * @return: string
	 */
	public function getClientId(){
<<<<<<< HEAD
		return $this->client_id;	
=======
		return $this->_client_id;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Setter: Client Secret
	 * @param: string $secret
	 * @return: void
	 */
	public function setClientSecret($secret){
<<<<<<< HEAD
		$this->client_secret = $secret;	
=======
		$this->_client_secret = $secret;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Getter: Client Id
	 * @return: string
	 */
	public function getClientSecret(){
<<<<<<< HEAD
		return $this->client_secret;	
=======
		return $this->_client_secret;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Setter: Callback Url
	 * @param: string $url
	 * @return: void
	 */
	public function setCallbackUrl($url){
<<<<<<< HEAD
		$this->callback_url = $url;	
=======
		$this->_callback_url = $url;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Getter: Callback Url
	 * @return: string
	 */
	public function getCallbackUrl(){
<<<<<<< HEAD
		return $this->callback_url;	
=======
		return $this->_callback_url;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Setter: Set Curl Timeout
	 * @param: integer|decimal|long $time
	 * @return: void
	 */
	public function setTimeout($time = 90){
<<<<<<< HEAD
		$this->timeout = $time;	
=======
		$this->_timeout = $time;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Getter: Get Curl Timeout
	 * @return: integer|decimal|long
	 */
	public function getTimeout(){
<<<<<<< HEAD
		return $this->timeout;	
=======
		return $this->_timeout;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Setter: Set Curl Timeout
	 * @param: integer|decimal|long $time
	 * @return: void
	 */
	public function setConnectTimeout($time = 20){
<<<<<<< HEAD
		$this->connect_timeout = $time;	
=======
		$this->_connect_timeout = $time;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Getter: Get Curl connect timeout
	 * @return: integer|decimal|long
	 */
	public function getConnectTimeout(){
<<<<<<< HEAD
		return $this->connect_timeout;	
=======
		return $this->_connect_timeout;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}
	
	/*
	 * Setter: Enfore Signed Request
	 * @param: boolean $secure
	 * @return: void
	 */
	public function setRequestSecure($secure){
<<<<<<< HEAD
		$this->secure = $secure;	
=======
		$this->_signed = $secure;	
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
	}	
	
	/*
     * Setter: User Access Token
     *
     * @param object|string $data
     *
     * @return void
     */
    private function setAccessToken($data){		
        $token = is_object($data) ? $data->access_token : $data;
<<<<<<< HEAD
        $this->access_token = $token;
=======
        $this->_access_token = $token;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
    }
	
    /*
     * Getter: User Access Token
     *
     * @return string
     */
    private function getAccessToken(){
<<<<<<< HEAD
        return $this->access_token;
=======
        return $this->_access_token;
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
    }
	
	/*
     * Extract response header content
     *
     * @param array
     *
     * @return array
     */
<<<<<<< HEAD
    protected function processHeaders($content){
=======
    private function _processHeaders($content){
>>>>>>> d70794b1756c72ede570206aa374b6e04ad591ec
        $headers = array();
        foreach (explode("\r\n", $content) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
                continue;
            }
            list($key, $value) = explode(':', $line);
            $headers[$key] = $value;
        }
        return $headers;
    }
}
