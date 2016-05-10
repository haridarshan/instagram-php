# Instagram-php
Easy-to-use PHP Library for Instagram APIs (Beta)

Installation/Usage
------------------
Download the Instagram-php Library
Add autoload.php in your PHP script

    ```
    require 'autoload.php';
    
    use Jet\Instagram\Instagram;
    
    $instagram = new Instagram(array(
      "ClientId" => <InstagramAppClientId>,
      "ClientSecret" => <InstagramAppClientSecret>,
	    "Callback" => <callback_url>
    ));
    
    $scope = [
    	"basic",
    	"likes",
    	"public_content",
    	"follower_list", 
    	"comments", 
    	"relationships"
    ];
    // To get the Instagram Login Url
    $insta_url = $instagram->getUrl("oauth/authorize",["scope" => $scope]);
    echo "<a href='{$insta_url}'>Login with Instagram</a>";
    
    // To get User's Access Token
    $insta_access_token = $instagram->getToken('oauth/access_token', $_GET['code'], true);
    
    // To get User Profile Details or to make any api call to instagram
    $user = $instagram->request("users/self", [ "access_token" => $insta_access_token ]);
    
    
    ```

