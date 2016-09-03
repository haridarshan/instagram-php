<?php
/**
 * The MIT License (MIT)
 * 
 * Copyright (c) 2016 Haridarshan Gorana
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */
namespace Haridarshan\Instagram;

use stdClass;
use InvalidArgumentException;
use Haridarshan\Instagram\Constants;
use Haridarshan\Instagram\Exceptions\InstagramException;
use Haridarshan\Instagram\HelperFactory;
use Haridarshan\Instagram\InstagramOAuth;

/**
 * PHP Instagram API wrapper class
 * 
 * @library			instagram-php
 * @license 		https://opensource.org/licenses/MIT MIT
 * @link			http://github.com/haridarshan/instagram-php Class Documentation
 * @link			http://instagram.com/developer/ API Documentation
 * @author			Haridarshan Gorana 	<hari.darshan@jetsynthesys.com>
 * @since			May 09, 2016
 * @copyright		Haridarshan Gorana
 * @version			2.2.2
 */
class Instagram
{
    /** @var InstagramApp */
    protected $app;
	
    /** @var string */
    protected $callbackUrl;
    
    /** @var array */
    protected $defaultScopes = array("basic", "public_content", "follower_list", "comments", "relationships", "likes");
	
    /** @var array */
    protected $scopes = array();
	
    /**
     * Random string indicating the state to prevent spoofing
     * @var string
     */
    protected $state;
		
    /** @var \GuzzleHttp\Client $client */
    protected $client;
	
    /** @var InstagramOAuth $oauthResponse */
    protected $oauthResponse;
	
    /**
     * Default Constructor
     * Instagram Configuration Data
	 *
     * @param array $config
	 *
	 * @throws InstagramException|InvalidArgumentException
	 * 
	 * @todo validate callback url
     */
    public function __construct(array $config = [])
    {
        if (!is_array($config)) {
            throw new InstagramException('Invalid Instagram Configuration data');
        }
		
		if (!$config['ClientId']) {
            throw new InstagramException('Missing "ClientId" key not supplied in config');
        }
		
		if (!$config['ClientSecret']) {
            throw new InstagramException('Missing "ClientSecret" key not supplied in config');
        }
		
		if (!$config['Callback']) {
            throw new InstagramException('Missing "Callback" key not supplied in config');
        }
		
		$this->app = new InstagramApp($config['ClientId'], $config['ClientSecret']);	
		
        $this->setCallbackUrl($config['Callback']);
        $this->state = isset($config['State']) ? $config['State'] : substr(md5(rand()), 0, 7);
        
        $this->client = HelperFactory::getInstance()->client(Constants::API_HOST);
    }
	
	/**
     * Returns InstagramApp entity.
     *
     * @return InstagramApp
     */
    public function getApp()
    {
        return $this->app;
    }
	
    /**
     * Make URLs for user browser navigation
	 *
     * @param array  $parameters
	 *
     * @return string
	 *
	 * @throws InstagramException
     */
    public function getLoginUrl(array $parameters)
    {
        if (!isset($parameters['scope'])) {
            throw new InstagramException("Missing or Invalid Scope permission used", 400);
        }
        if (count(array_diff($parameters['scope'], $this->defaultScopes)) !== 0) {
            throw new InstagramException("Missing or Invalid Scope permission used", 400);
        }
		
        $this->scopes = $parameters['scope'];
		
		$loginUrl = new LoginUrl(
			$this->getApp(),
			$this->getCallbackUrl(),
			$this->getState(),
			$this->scopes
		);
		
		return $loginUrl->loginUrl();
    }
	
    /**
     * Get the Oauth Access Token of a user from callback code
	 *
     * @param string $code - Oauth2 Code returned with callback url after successfull login
	 *
     * @return InstagramOAuth
     */
    public function oauth($code)
    {
        $options = array(
            "grant_type" => "authorization_code",
            "client_id" => $this->app->getId(),
            "client_secret" => $this->app->getSecret(),
            "redirect_uri" => $this->getCallbackUrl(),
            "code" => $code,
            "state" => $this->state
        );
		
		$response = HelperFactory::getInstance()->request($this->client, Constants::API_TOKEN, $options, 'POST');
		
        $this->oauthResponse = new InstagramOAuth(
			json_decode($response->getBody()->getContents())
		);
		
        return $this->oauthResponse;
    }
	
    /**
     * Setter: Callback Url
	 *
     * @param string $url
	 *
     * @return void
     */
    public function setCallbackUrl($url)
    {
        $this->callbackUrl = $url;
    }
	
    /**
     * Getter: Callback Url
	 *
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }
	
    /**
     * Get InstagramOAuth
	 *
     * @return InstagramOAuth
     */
    public function getOAuth()
    {
        if ($this->oauthResponse instanceof InstagramOAuth) {
            return $this->oauthResponse;
        }
		
		$accessToken = new stdClass;
		$accessToken->access_token = null;
		
        $this->oauthResponse = new InstagramOAuth($accessToken);
        return $this->oauthResponse;
    }
	
    /**
	 * Get Http Client
	 *
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->client;
    }
	
    /**
     * Set User Access Token
	 *
     * @param string $token
	 *
     * @return void
     */
    public function setAccessToken($token)
    {
        if (!$this->oauthResponse instanceof InstagramOAuth) {
            $this->oauthResponse = new InstagramOAuth(json_decode(json_encode(["access_token" => $token])));
        }
    }
	
    /**
     * Get state value
	 *
     * @return string|mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Get a string containing the version of the library.
	 *
     * @return string
     */
    public function getLibraryVersion()
    {
        return Constants::VERSION;
    }
}
