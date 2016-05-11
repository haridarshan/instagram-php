<?php
require __DIR__ . '/../vendor/autoload.php';

define('CLIENT_ID', getenv('TEST_CLIENT_ID'));
define('CLIENT_SECRET', getenv('TEST_CLIENT_SECRET'));
define('CALLBACK_URL', getenv('TEST_CALLBACK_URL'));
define('CODE', getenv('TEST_CODE'));
