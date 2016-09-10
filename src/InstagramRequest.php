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
 */
namespace Haridarshan\Instagram;

use Haridarshan\Instagram\Exceptions\InstagramRequestException;
use Haridarshan\Instagram\Exceptions\InstagramResponseException;
use Haridarshan\Instagram\Exceptions\InstagramThrottleException;

/**
 * InstagramRequest class
 *
 * @library			instagram-php
 *
 * @license 		https://opensource.org/licenses/MIT MIT
 *
 * @link			http://github.com/haridarshan/instagram-php Class Documentation
 * @link			http://instagram.com/developer/ API Documentation
 *
 * @author			Haridarshan Gorana 	<hari.darshan@jetsynthesys.com>
 *
 * @since			May 09, 2016
 *
 * @copyright		Haridarshan Gorana
 *
 * @version			2.2.2
 */
class InstagramRequest
{
    /** @var string $path */
    protected $path;

    /** @var array $params */
    protected $params;

    /** @var string $method */
    protected $method;

    /**
     * Remaining Rate Limit
     * Sandbox = 500
     * Live = 5000
     *
     * @var array
     */
    protected $xRateLimitRemaining = 500;

    /** @var InstagramResponse $response */
    protected $response;

    /** @var Instagram $instagram */
    protected $instagram;

    /**
     * Create the request and execute it to get the response
     *
     * @param Instagram $instagram
     * @param string    $path
     * @param array     $params
     * @param string    $method
     */
    public function __construct(Instagram $instagram, $path, array $params = [], $method = 'GET')
    {
        $this->instagram = $instagram;
        $this->path = $path;
        $this->params = $params;
        $this->method = $method;
    }

    /**
     * Execute the Instagram Request
     *
     * @param void
     *
     * @throws InstagramResponseException
     *
     * @return InstagramResponse
     */
    protected function execute()
    {
        $authMethod = '?access_token=' . $this->params['access_token'];
        $endpoint = Constants::API_VERSION . $this->path . (('GET' === $this->method) ? '?' . http_build_query($this->params) : $authMethod);
        $endpoint .= (strstr($endpoint, '?') ? '&' : '?') . 'sig=' . static::generateSignature($this->instagram->getApp(), $this->path, $this->params);

        $request = HelperFactory::getInstance()->request($this->instagram->getHttpClient(), $endpoint, $this->params, $this->method);

        if ($request === null) {
            throw new InstagramResponseException('400 Bad Request: instanceof InstagramResponse cannot be null', 400);
        }
        $this->response = new InstagramResponse($request);
        $this->xRateLimitRemaining = $this->response->getHeader('X-Ratelimit-Remaining');
    }

    /**
     * Check Access Token is present. If not throw InstagramRequestException
     *
     * @throws InstagramRequestException
     */
    protected function isAccessTokenPresent()
    {
        if (!isset($this->params['access_token'])) {
            throw new InstagramRequestException("{$this->path} - api requires an authenticated users access token.", 400);
        }
    }

    /**
     * Get Response
     *
     * @return InstagramResponse
     */
    public function getResponse()
    {
        $this->isRateLimitReached();
        $this->isAccessTokenPresent();
        $oauth = $this->instagram->getOAuth();

        if (!$oauth->isAccessTokenSet()) {
            $oauth->setAccessToken($this->params['access_token']);
        }

        $this->execute();

        return $this->response;
    }

    /**
     * Check whether api rate limit is reached or not
     *
     * @throws InstagramThrottleException
     */
    private function isRateLimitReached()
    {
        if (!$this->getRateLimit()) {
            throw new InstagramThrottleException('400 Bad Request : You have reached Instagram API Rate Limit', 400);
        }
    }

    /**
     * Secure API Request by using endpoint, paramters and API secret
     *
     * @see https://www.instagram.com/developer/secure-api-requests/
     *
     * @param InstagramApp $app
     * @param string       $endpoint
     * @param array        $params
     *
     * @return string (Signature)
     */
    public static function generateSignature(InstagramApp $app, $endpoint, $params)
    {
        $signature = $endpoint;
        ksort($params);
        foreach ($params as $key => $value) {
            $signature .= "|$key=$value";
        }

        return hash_hmac('sha256', $signature, $app->getSecret(), false);
    }

    /**
     * Get Api Rate Limit
     *
     * @return int
     */
    public function getRateLimit()
    {
        return $this->xRateLimitRemaining;
    }
}
