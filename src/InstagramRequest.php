<?php
namespace Haridarshan\Instagram;

use Haridarshan\Instagram\Constants;
use Haridarshan\Instagram\Exceptions\InstagramRequestException;
use Haridarshan\Instagram\Exceptions\InstagramResponseException;
use Haridarshan\Instagram\Exceptions\InstagramThrottleException;
use Haridarshan\Instagram\HelperFactory;
use Haridarshan\Instagram\Instagram;
use Haridarshan\Instagram\InstagramResponse;

class InstagramRequest
{
    /** @var string $path */
    private $path;
    
    /** @var array $params */
    private $params;
    
    /** @var string $method */
    private $method;

    /*
    * Remaining Rate Limit
    * Sandbox = 500
    * Live = 5000
    * @var array $x_rate_limit_remaining
    */
    private $xRateLimitRemaining = 500;
    
    /** @var InstagramResponse $response */
    protected $response;
    
    /** @var Instagram $instagram */
    protected $instagram;
    
    /*
     * Create the request and execute it to get the response
     * @param Instagram $instagram
     * @param string $path
     * @param array $params
     * @param string $method
     */
    public function __construct(Instagram $instagram, $path, array $params = array(), $method = 'GET')
    {
        $this->instagram = $instagram;
        $this->path = $path;
        $this->params = $params;
        $this->method = $method;
    }
    
    /*
     * Execute the Instagram Request
     * @param void
     * @return InstagramResponse
     */
    protected function execute()
    {
        $this->isRateLimitReached();
        $this->isAccessTokenPresent();
        $oauth = $this->instagram->getOAuth();
        if (!$oauth->isAccessTokenSet()) {
            $oauth->setAccessToken($this->params['access_token']);
        }
        $authentication_method = '?access_token='.$this->params['access_token'];
        $endpoint = Constants::API_VERSION.$this->path.(('GET' === $this->method) ? '?'.http_build_query($this->params) : $authentication_method);
        $endpoint .= (strstr($endpoint, '?') ? '&' : '?').'sig='.static::generateSignature($this->instagram->getClientSecret(), $this->path, $this->params);
        
		$request = HelperFactory::request($this->instagram->getHttpClient(), $endpoint, $this->params, $this->method);
		if ($request !== null) {
        	$this->response = new InstagramResponse($request);
        	$this->xRateLimitRemaining = $this->response->getHeader('X-Ratelimit-Remaining');
		} else {
			throw new InstagramResponseException("400 Bad Request: instanceof InstagramResponse cannot be null", 400);
		}
    }
    
    /*
     * Check Access Token is present. If not throw InstagramRequestException
     * @throws InstagramRequestException
     */
    protected function isAccessTokenPresent()
    {
        if (!isset($this->params['access_token'])) {
            throw new InstagramRequestException("{$this->path} - api requires an authenticated users access token.", 400);
        }
    }
    
    /*
     * Get Response
     * @return InstagramResponse
     */
    public function getResponse()
    {
        $this->execute();
        return $this->response;
    }
    
    /*
     * Check whether api rate limit is reached or not
     * @throws InstagramThrottleException
     */
    private function isRateLimitReached()
    {
        if (!$this->getRateLimit()) {
            throw new InstagramThrottleException("400 Bad Request : You have reached Instagram API Rate Limit", 400);
        }
    }
    
    /*
     * Secure API Request by using endpoint, paramters and API secret
     * copy from Instagram API Documentation: https://www.instagram.com/developer/secure-api-requests/
     *
     * @param string $secret
     * @param string $endpoint
     * @param array $params
     *
     * @return string (Signature)
     */
    public static function generateSignature($secret, $endpoint, $params)
    {
        $signature = $endpoint;
        ksort($params);
        foreach ($params as $key => $value) {
            $signature .= "|$key=$value";
        }
        return hash_hmac('sha256', $signature, $secret, false);
    }
    
    /*
     * @return int
     */
    public function getRateLimit()
    {
        return $this->xRateLimitRemaining;
    }
}
