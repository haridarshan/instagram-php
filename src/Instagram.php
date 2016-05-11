<?php
namespace Haridarshan\Instagram;

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
class Instagram {
	/*
	* Library Version
	*/
	const VERSION = '1.0.1';
	
	/*
	* API End Point
	*/  
	const API_VERSION = 'v1/';
	
	/*
	* API End Point
	*/  
	const API_HOST = 'https://api.instagram.com/';
		
	/*
	* Client Id
	* @var: string
	*/
	private $client_id;
	
	/*
	* Client Secret
	* @var: string
	*/
	private $client_secret;
	
	/*
	* Instagram Callback url
	* @var: string
	*/
	private $callback_url;
	
	/*
	* Oauth Access Token
	* @var: string
	*/
	private $access_token;
	
	/*
	* Instagram Available Scopes
	* @var: array of strings
	*/
	private $default_scopes = array("basic", "public_content", "follower_list", "comments", "relationships", "likes");
	
	/*
	* User's Scope
	* @var: array of strings
	*/
	private $scopes = array();
	
	/*
	* Enable secure request
	* @var: boolean
	*/ 
	private $secure = true;
	
	/*
	* Curl timeout
	* @var: integer|decimal|long
	*/
	private $timeout = 90;
	
	/*
	* Curl Connect timeout
	* @var: integer|decimal|long
	*/
	private $connect_timeout = 20;
	
	/*
	* Remaining Rate Limit
	* Sandbox = 500
	* Live = 5000
	*/
	private $x_rate_limit_remaining = 500;
	
	/*
	* @var GuzzleHttp\ClientInterface $http
	*/
	private $client;
	
	/*
	* @var GuzzleHttp\Psr7\Response $response
	*/
	private $response;
		
	/*
	* Default Constructor 
	* Instagram Configuration Data
	* @param array|object|string $config
	*/
	public function __construct($config) {		
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
			throw new \Haridarshan\Instagram\InstagramException('Invalid Instagram Configuration data');			
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
	public function getUrl($path, array $parameters) {		
		if (is_array($parameters)) {			
			if (isset($parameters['scope']) && count(array_diff($parameters['scope'], $this->default_scopes)) === 0) {
				$this->scopes = $parameters['scope']; 
			} else {
				throw new \Haridarshan\Instagram\InstagramException("Missing or Invalid Scope permission used");
			}
			
			$query = 'client_id='.$this->getClientId().'&redirect_uri='.urlencode($this->getCallbackUrl()).'&response_type=code';
			
			if (isset($this->scopes)) {
				$scope = urlencode(str_replace(",", " ", implode(",", $parameters['scope'])));

				$query .= "&scope=$scope";
			}
			
			return sprintf('%s%s?%s', self::API_HOST, $path, $query);
		}
		throw new \Haridarshan\Instagram\InstagramException("Missing or Invalid Scope permission used");
	}
	
	/*
	* Get the Oauth Access Token of a user from callback code
	* 
	* @param string $path - OAuth Access Token Path
	* @param string $code - Oauth2 Code returned with callback url after successfull login
	* @param boolean $token - true will return only access token
	*/
	public function getToken($path, $code, $token = false) {
		$options = array(
			"grant_type" => "authorization_code",
			"client_id" => $this->getClientId(),
			"client_secret" => $this->getClientSecret(),
			"redirect_uri" => $this->getCallbackUrl(),
			"code" => $code
		);
				
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => self::API_HOST
		]);
		
		
		$this->execute($path, $options, 'POST');
		
		if (isset($this->response->code)) {
			throw new \Haridarshan\Instagram\InstagramException("return status code: ".$this->response->code." type: ".$this->response->error_type." message: ".$this->response->error_message);
		}
				
		$this->setAccessToken($this->response);
				
		return !$token ? $this->response : $this->response->access_token;
	}
	
	/*
	* Method to make GuzzleHttp Client Request to Instagram APIs
	*
	* @param string $endpoint
	* @param array|string $options in case of POST [optional]
	* @param string $method GET|POST
	*/
	protected function execute($endpoint, $options, $method = 'GET') {
		
		if (!isset($this->client)) {
			$this->client = new \GuzzleHttp\Client([
				'base_uri' => self::API_HOST
			]);
		}	
		switch ($method) {
			case 'GET':
				$result = $this->client->request(
					$method, 
					$endpoint, 
					[
						'headers' => [
							'Accept'     => 'application/json'
						]
					]
				);
				$limit = $result->getHeader('x-ratelimit-remaining');
				$this->x_rate_limit_remaining = $limit[0];
				
				$this->response = json_decode($result->getBody()->getContents());
				
				break;
			case 'POST': 
				if (is_array($options)) {
					$body = http_build_query($options);
				} else {
					$body = ltrim($options, '&');
				}
				
				try {
					$this->response = json_decode($this->client->request(
						$method, 
						$endpoint, 
						[
							'headers' => [
								'Accept'     => 'application/json'
							],
							'body' => $body
						]
					)->getBody()->getContents());
				} catch (\GuzzleHttp\Exception\ClientException $e) {
					throw new \Haridarshan\Instagram\InstagramException($e->getMessage());
				}
				break;
			case 'DELETE':
				break;
		}
	}
		
	/*
	* Secure API Request by using endpoint, paramters and API secret
	* copy from Instagram API Documentation: https://www.instagram.com/developer/secure-api-requests/
	* 
	* @param string $endpoint
	* @param string $auth
	* @param array|string $params
	*
	* return string (Signature)
	*/
	protected function secureRequest($endpoint, $auth, $params) {	
		if (!is_array($params)) {
			$params = array();	
		}
		
		if ($auth) {
			list($key, $value) = explode("=", substr($auth, 1), 2);
			$params[$key] = $value;
		}
		
		$signature = $endpoint;
		ksort($params);
		
		foreach($params as $key => $value) {
			$signature .= "|$key=$value";	
		}
					
		return hash_hmac('sha256', $signature, $this->getClientSecret(), false);
	}
	
	/* 
	* Method to make api requests
	*
	* return mixed
	*/
	public function request($path, $params = null, $method = 'GET') {
		if ($this->x_rate_limit_remaining < 1) {
			throw new \Haridarshan\Instagram\InstagramException("You have reached Instagram API Rate Limit");
		} else {			
			if (!isset($params['access_token'])) {
				throw new \Haridarshan\Instagram\InstagramException("$path - api requires an authenticated users access token.");
			}
			
			$data = ($params != null) ? $params : array();			
			
			// If api call doesn't requires authentication
			if (isset($params['access_token']) && !isset($this->access_token)) {
				$this->setAccessToken($params['access_token']);								
			}
			
			$authentication_method = '?access_token='.$this->access_token;
			// Need to remove the access_token from $params array
			unset($params['access_token']);			
						
			$param = (isset($params) && is_array($params)) ? '&'.http_build_query($params) : null;
			
			$endpoint = self::API_VERSION.$path.$authentication_method.(('GET' === $method) ? $param : null);
			
			if ($this->secure) {
				$endpoint .= (strstr($endpoint, '?') ? '&' : '?').'sig='.$this->secureRequest($path, $authentication_method, $data);
			}
						
			$this->execute($endpoint, $data);
			
			return $this->response;		
		}
	}
	
	/*
	* Setter: Client Id
	* @param string $clientId
	* @return void
	*/
	public function setClientId($clientId) {
		$this->client_id = $clientId;	
	}
	
	/*
	* Getter: Client Id
	* @return string
	*/
	public function getClientId() {
		return $this->client_id;	
	}
	
	/*
	* Setter: Client Secret
	* @param string $secret
	* @return void
	*/
	public function setClientSecret($secret) {
		$this->client_secret = $secret;	
	}
	
	/*
	* Getter: Client Id
	* @return string
	*/
	public function getClientSecret() {
		return $this->client_secret;	
	}
	
	/*
	* Setter: Callback Url
	* @param string $url
	* @return void
	*/
	public function setCallbackUrl($url) {
		$this->callback_url = $url;	
	}
	
	/*
	* Getter: Callback Url
	* @return string
	*/
	public function getCallbackUrl() {
		return $this->callback_url;	
	}
	
	/*
	* Setter: Set Curl Timeout
	* @param integer|decimal|long $time
	* @return void
	*/
	public function setTimeout($time = 90) {
		$this->timeout = $time;	
	}
	
	/*
	* Getter: Get Curl Timeout
	* @return integer|decimal|long
	*/
	public function getTimeout() {
		return $this->timeout;	
	}
	
	/*
	* Setter: Set Curl Timeout
	* @param integer|decimal|long $time
	* @return void
	*/
	public function setConnectTimeout($time = 20) {
		$this->connect_timeout = $time;	
	}
	
	/*
	* Getter: Get Curl connect timeout
	* @return integer|decimal|long
	*/
	public function getConnectTimeout() {
		return $this->connect_timeout;	
	}
	
	/*
	* Setter: Enfore Signed Request
	* @param boolean $secure
	* @return void
	*/
	public function setRequestSecure($secure) {
		$this->secure = $secure;	
	}	
	
	/*
	* Setter: User Access Token
	*
	* @param object|string $data
	*
	* @return void
	*/
	private function setAccessToken($data) {		
		$token = is_object($data) ? $data->access_token : $data;
		$this->access_token = $token;
	}
	
	/*
	* Getter: User Access Token
	*
	* @return string
	*/
    public function getAccessToken() {
        return $this->access_token;
    }
		
	/*
	* Get a string containing the version of the library.
	*
	* @return string
	*/
	public function getLibraryVersion() {
		return self::VERSION;
	}
}
