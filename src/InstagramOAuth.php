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
use Haridarshan\Instagram\Exceptions\InstagramOAuthException;

/**
 * Instagram Oauth class
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
class InstagramOAuth
{
    /** @var string $accessToken */
    protected $accessToken;
    
    /** @var object $user */
    protected $user;
    
    /** 
     * InstagramOAuth Entity 
     * 
     * @param stdClass $oauth
     * 
     * @throws InstagramOAuthException
     */
    public function __construct(stdClass $oauth)
    {
        if (empty($oauth)) {
            throw new InstagramOAuthException("Bad Request 400 empty Response", 400);
        }
		
        $this->accessToken = $oauth->access_token;
        $this->user = isset($oauth->user) ? $oauth->user : null;
    }
    
    /**
     * Get Access Token
     * 
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    
    /**
     * Set Access Token
     * 
     * @param string $token
     * 
     * @return void
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }
    
    /**
     * If AccessToken is set return true else false
     * 
     * @return bool
     */
    public function isAccessTokenSet()
    {
        return isset($this->accessToken);
    }
    
    /**
     * Get User Info
     * 
     * @return object
     */
    public function getUserInfo()
    {
        return $this->user;
    }
}
