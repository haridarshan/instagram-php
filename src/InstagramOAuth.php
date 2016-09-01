<?php
namespace Haridarshan\Instagram;

use Haridarhan\Instagram\Exceptions\InstagramOAuthException;

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
 * @version			2.1
 */
class InstagramOAuth
{
    /** @var string $accessToken */
    private $accessToken;
    
    /** @var object $user */
    private $user;
    
    /*
     * @param \stdClass $oauth
     * @throws InstagramOAuthException
     */
    public function __construct(\stdClass $oauth)
    {
        if (empty($oauth)) {
            throw new InstagramOAuthException("Bad Request 400 empty Response", 400);
        }
        $this->accessToken = $oauth->access_token;
        $this->user = isset($oauth->user) ? $oauth->user : null;
    }
    
    /*
     * Get Access Token
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    
    /*
     * Set Access Token
     * @param string $token
     * @return void
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }
    
    /*
     * If AccessToken is set return true else false
     * @return bool
     */
    public function isAccessTokenSet()
    {
        return isset($this->accessToken);
    }
    
    /*
     * Get User Info
     * @return object
     */
    public function getUserInfo()
    {
        return $this->user;
    }
}
