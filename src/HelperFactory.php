<?php
namespace Haridarshan\Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Haridarshan\Instagram\Exceptions\InstagramException;
use Haridarshan\Instagram\Exceptions\InstagramOAuthException;
use Haridarshan\Instagram\Exceptions\InstagramServerException;

class HelperFactory
{
    /** @var HelperFactory The reference to *HelperFactory* instance of this class */
    private static $instance;
	
    /** @var Response|ResponseInterface $response */
    protected static $response;
    
    /** @var Stream $stream */
    protected static $stream;
    
    /** @var object $content */
    protected static $content;
    
    /*
     * Returns the *HelperFactory* instance of this class.
     *
     * @return HelperFactory The *HelperFactory* instance.
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        
        return self::$instance;
    }
	
    /*
     * Protected constructor to prevent creating a new instance of the
     * *HelperFactory* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
        // a factory constructor should never be invoked
    }
    
    /*
     * Factory Client method to create \GuzzleHttp\Client object
     * @param string $uri
     * @return Client
     */
    public function client($uri)
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
    public function request(Client $client, $endpoint, $options, $method = 'GET')
    {
        try {
            return $client->request($method, $endpoint, [
                'form_params' => $options
            ]);
        } catch (ClientException $exception) {
            static::throwException(static::extractOriginalExceptionMessage($exception), $exception);
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
     * @param ClientException $exception
     * @return
     */
    protected static function extractOriginalExceptionMessage(ClientException $exception)
    {
        self::$response = $exception->getResponse();
        self::$stream = self::$response->getBody();
        self::$content = self::$stream->getContents();
        if (!self::$content) {
            throw new InstagramServerException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
        return json_decode(self::$content);
    }
    
    /*
     * @param \stdClass $object
	 * @param ClientException $exMessage
     * @return void
     */
    protected static function throwException(\stdClass $object, ClientException $exMessage)
    {
        $exception = static::getExceptionMessage($object);
        if (stripos($exception['error_type'], "oauth") !== false) {
            throw new InstagramOAuthException(
                json_encode(array("Type" => $exception['error_type'], "Message" => $exception['error_message'])),
                $exception['error_code'],
                $exMessage
            );
        }
        throw new InstagramException(
            json_encode(array("Type" => $exception['error_type'], "Message" => $exception['error_message'])),
            $exception['error_code'],
            $exMessage
        );
    }
	
    /*
	 * @param \stdClass $object
	 * @return array
	 */
    protected static function getExceptionMessage(\stdClass $object)
    {
        $message = array();		
        $message['error_type'] = isset($object->meta) ? $object->meta->error_type : $object->error_type;
        $message['error_message'] = isset($object->meta) ? $object->meta->error_message : $object->error_message;
        $message['error_code'] = isset($object->meta) ? $object->meta->code : $object->code;
        return $message;
    }
	
    /*
     * Private clone method to prevent cloning of the instance of the
     * *HelperFactory* instance.
     *
     * @return void
     */
    private function __clone()
    {
        // a factory clone should never be invoked
    }
}
