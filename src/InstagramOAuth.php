<?php
namespace Haridarshan\Instagram;

use Haridarhan\Instagram\Exceptions\InstagramOAuthException;

class InstagramOAuth
{
    /** @var string $accessToken */
    private $accessToken;
    
    /** @var object $user */
    private $user;
    
    /*
     * @param object $oauth
     * @throws InstagramOAuthException
     */
    public function __construct($oauth)
    {
        if (!empty($oauth)) {
            $this->accessToken = $oauth->access_token;
            $this->user = isset($oauth->user) ? $oauth->user : null;
        } else {
            throw new InstagramOAuthException("Bad Request 400 empty Response", 400);
        }
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
        return isset($this->accessToken) ? true : false;
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
