<?php
/*
* Instagram API class
*
* API Documentation: http://instagram.com/developer/
* Class Documentation: https://github.com/haridarshan/Instagram-php
*
* @author Haridarshan Gorana
* @since May 09, 2016
* @copyright Haridarshan Gorana
* @version 2.0
* @license: MIT
*
*/
namespace Haridarshan\Instagram;

use Haridarshan\Instagram\Constants;
use Haridarshan\Instagram\Exceptions\InstagramException;
use Haridarshan\Instagram\HelperFactory;
use Haridarshan\Instagram\InstagramOAuth;

class Instagram
{
    /** @var string */
    private $clientId;
	
    /** @var string */
    private $clientSecret;
	
    /** @var string */
    private $callbackUrl;
    
    /** @var array<string> */
    private $defaultScopes = array("basic", "public_content", "follower_list", "comments", "relationships", "likes");
	
    /** @var array<string> */
    private $scopes = array();
	
    /*
    * Random string indicating the state to prevent spoofing
    * @var string
    */
    private $state;
		
    /** @var \GuzzleHttp\Client $client */
    protected $client;
	
    /** @var object $oauthResponse */
    private $oauthResponse;
	
    /*
     * Default Constructor
     * Instagram Configuration Data
     * @param array|object|string $config
     */
    public function __construct($config)
    {
        if (is_array($config)) {
            $this->setClientId($config['ClientId']);
            $this->setClientSecret($config['ClientSecret']);
            $this->setCallbackUrl($config['Callback']);
            $this->state = isset($config['State']) ? $config['State'] : substr(md5(rand()), 0, 7);
        } else {
            throw new InstagramException('Invalid Instagram Configuration data', 400);
        }
        $this->client = HelperFactory::client(Constants::API_HOST);
    }
	
    /*
     * Make URLs for user browser navigation
     * @param array  $parameters
     * @return string
     */
    public function getLoginUrl(array $parameters)
    {
        if (!isset($parameters['scope'])) {
            throw new InstagramException("Missing or Invalid Scope permission used", 400);
        }
        if (count(array_diff($parameters['scope'], $this->defaultScopes)) === 0) {
            $this->scopes = $parameters['scope'];
        } else {
            throw new InstagramException("Missing or Invalid Scope permission used", 400);
        }
        $query = 'client_id='.$this->getClientId().'&redirect_uri='.urlencode($this->getCallbackUrl()).'&response_type=code&state='.$this->state;
        $query .= isset($this->scopes) ? '&scope='.urlencode(str_replace(",", " ", implode(",", $parameters['scope']))) : '';
        return sprintf('%s%s?%s', Constants::API_HOST, Constants::API_AUTH, $query);
    }
	
    /*
     * Get the Oauth Access Token of a user from callback code
     * @param string $code - Oauth2 Code returned with callback url after successfull login
     * @return InstagramOAuth
     */
    public function oauth($code)
    {
        $options = array(
            "grant_type" => "authorization_code",
            "client_id" => $this->getClientId(),
            "client_secret" => $this->getClientSecret(),
            "redirect_uri" => $this->getCallbackUrl(),
            "code" => $code,
            "state" => $this->state
        );
		$response = HelperFactory::request($this->client, Constants::API_TOKEN, $options, 'POST');
		$this->oauthResponse = new InstagramOAuth(
			json_decode($response->getBody()->getContents())
		);
        return $this->oauthResponse;
    }
   
    /*
     * Set Client Id
     * @param string $clientId
     * @return void
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }
	
    /*
     * Get Client Id
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }
	
    /*
     * Set Client Secret
     * @param string $secret
     * @return void
     */
    public function setClientSecret($secret)
    {
        $this->clientSecret = $secret;
    }
	
    /*
     * Getter: Client Id
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
	
    /*
     * Setter: Callback Url
     * @param string $url
     * @return void
     */
    public function setCallbackUrl($url)
    {
        $this->callbackUrl = $url;
    }
	
    /*
     * Getter: Callback Url
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }
	
    /*
     * Get InstagramOAuth
     * @return InstagramOAuth
     */
    public function getOAuth()
    {
        if ($this->oauthResponse instanceof InstagramOAuth) {
            return $this->oauthResponse;
        } else {
            $this->oauthResponse = new InstagramOAuth(json_decode(json_encode(["access_token" => null])));
            return $this->oauthResponse;
        }
    }
    /*
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->client;
    }
	
    /*
     * Setter: User Access Token
     * @param string $token
     * @return void
     */
    public function setAccessToken($token)
    {
        if (!$this->oauthResponse instanceof InstagramOAuth) {
            $this->oauthResponse = new InstagramOAuth(json_decode(json_encode(["access_token" => $token])));
        }
    }

    /*
     * Get a string containing the version of the library.
     * @return string
     */
    public function getLibraryVersion()
    {
        return Constants::VERSION;
    }
	
    /*
     * Get state value
     * @return string|mixed
     */
    public function getState()
    {
        return $this->state;
    }
}
