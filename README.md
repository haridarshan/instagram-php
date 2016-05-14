# Instagram-php
Easy-to-use PHP Library for Instagram APIs (Beta)

[![License](https://img.shields.io/packagist/l/haridarshan/instagram-php.svg?style=flat)](https://packagist.org/packages/haridarshan/instagram-php) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/haridarshan/instagram-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/haridarshan/instagram-php/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/haridarshan/instagram-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/haridarshan/instagram-php/build-status/master) [![Latest Stable Version](https://img.shields.io/packagist/v/haridarshan/instagram-php.svg)](https://packagist.org/packages/haridarshan/instagram-php) [![Total Downloads](http://img.shields.io/packagist/dm/haridarshan/instagram-php.svg?style=flat)](https://packagist.org/packages/haridarshan/instagram-php) [![Issues Count](https://img.shields.io/github/issues/haridarshan/instagram-php.svg)](https://github.com/haridarshan/instagram-php/issues) [![Code Coverage](https://scrutinizer-ci.com/g/haridarshan/instagram-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/haridarshan/instagram-php/?branch=master)

> **Note:** Any app created before Nov 17, 2015 will continue to function until June 2016. After June 2016, the app will automatically be moved to Sandbox Mode if it wasn't approved through the review process. The Instagram API requires an access_token from authenticated users for each endpoint. We no longer support making requests using just the client_id.

##Installation

To install, use composer:

```
composer require haridarshan/instagram-php
```

## Usage

```php
<?php
require 'vendor/autoload.php';

use Haridarshan\Instagram\Instagram;
?>
```

### Authorization Code Flow

```php
<?php 

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

?>
```

### Get Access Token

```php
<?php

// To get User's Access Token
$insta_access_token = $instagram->getToken('oauth/access_token', $_GET['code'], true);

?>
```

### Request to Instagram APIs

```php
<?php

// To get User Profile Details or to make any api call to instagram
$user = $instagram->request("/users/self", [ "access_token" => $insta_access_token ]);

$media_comment = $instagram->request(
  "/media/{media-id}/comments", 
  [ "access_token" => $insta_access_token, "text" => "{comment}" ], 
  "POST"
);

$delete_comment = $instagram->request(
  "/media/{media-id}/comments/{comment-id}", 
  [ "access_token" => $insta_access_token], 
  "DELETE"
);

?>
```

