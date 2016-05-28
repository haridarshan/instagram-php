<?php
namespace Haridarshan\Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Haridarshan\Instagram\Exceptions\InstagramException;
use Haridarshan\Instagram\Exceptions\InstagramOAuthException;
use Haridarshan\Instagram\Exceptions\InstagramServerException;

class HelperFactory
{
    /** @var Response $response */
    protected static $response;
    
    /** @var Stream $stream */
    protected static $stream;
    
    /** @var object $content */
    protected static $content;
    
    private function __construct()
    {
        // a factory constructor should never be invoked
    }
    
    /*
     * Factory Client method to create \GuzzleHttp\Client object
     * @param string $uri
     * @return Client
     */
    public static function client($uri)
    {
        return new Client([
            'base_uri' => $uri
        ]);
    }

    /*	
     * @param Client $client
     * @param string $endpoint
     * @param array|string $options
     * @param string $method
     * @return Response
     * @throws InstagramOAuthException
     * @throws InstagramException
     */
    public static function request(Client $client, $endpoint, $options, $method = 'GET')
    {
        try {
            return $client->request($method, $endpoint, [
                'headers' => ['Accept' => 'application/json'],
                'body' => static::createBody($options, $method)
            ]);
        } catch (ClientException $e) {
            static::throwException(static::extractOriginalExceptionMessage($e), $e);
        }
    }
    
    /*
     * Create body for Guzzle client request
     * @param array|null|string $options
     * @param string $method GET|POST
     * @return string|mixed
     */
    protected static function createBody($options, $method)
    {
        return ('GET' !== $method) ? is_array($options) ? http_build_query($options) : ltrim($options, '&') : null;
    }
    
    /*
     * Method to extract all exceptions for Guzzle ClientException
     * @param ClientException $e
     * @return
     */
    protected static function extractOriginalExceptionMessage(ClientException $e)
    {
        self::$response = $e->getResponse();
        self::$stream = self::$response->getBody();
        self::$content = self::$stream->getContents();
        if (empty(self::$content)) {
            throw new InstagramServerException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        } else {
            return json_decode(self::$content);
        }
    }
    
    /*
     * @param \stdClass $object
	 * @param ClientException $e
     * @return void
     */
    protected static function throwException(\stdClass $object, ClientException $e)
    {
        $exception = array();
        if (isset($object->meta)) {
            $exception['error_type'] = $object->meta->error_type;
            $exception['error_message'] = $object->meta->error_message;
            $exception['error_code'] = $object->meta->code;
        } else {
            $exception['error_type'] = $object->error_type;
            $exception['error_message'] = $object->error_message;
            $exception['error_code'] = $object->code;
        }
        if (stripos($exception['error_type'], "oauth") !== false) {
            throw new InstagramOAuthException(
                json_encode(array("Type" => $exception['error_type'], "Message" => $exception['error_message'])),
                $exception['error_code'],
                $e
            );
        }
		throw new InstagramException(
			json_encode(array("Type" => $exception['error_type'], "Message" => $exception['error_message'])),
			$exception['error_code'],
			$e
		);
    }
}
