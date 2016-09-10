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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Haridarshan\Instagram\Exceptions\InstagramException;
use Haridarshan\Instagram\Exceptions\InstagramOAuthException;
use Haridarshan\Instagram\Exceptions\InstagramServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * HelperFactory class
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

    /**
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

    /**
     * Protected constructor to prevent creating a new instance of the
     * *HelperFactory* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
        // a factory constructor should never be invoked
    }

    /**
     * Factory Client method to create \GuzzleHttp\Client object
     *
     * @param string $uri
     *
     * @return Client
     */
    public function client($uri)
    {
        return new Client([
            'base_uri' => $uri,
        ]);
    }

    /**
     * Sends request to Instagram Api Endpoints
     *
     * @param Client       $client
     * @param string       $endpoint
     * @param array|string $options
     * @param string       $method
     *
     * @throws InstagramOAuthException|InstagramException
     *
     * @return Response
     */
    public function request(Client $client, $endpoint, $options, $method = 'GET')
    {
        try {
            return $client->request($method, $endpoint, [
                'form_params' => $options,
            ]);
        } catch (ClientException $exception) {
            static::throwException(static::extractOriginalExceptionMessage($exception), $exception);
        }
    }

    /**
     * Create body for Guzzle client request
     *
     * @param array|null|string $options
     * @param string            $method  GET|POST
     *
     * @return string|mixed
     */
    protected static function createBody($options, $method)
    {
        return ('GET' !== $method) ? is_array($options) ? http_build_query($options) : ltrim($options, '&') : null;
    }

    /*
     * Method to extract all exceptions for Guzzle ClientException
     *
     * @param ClientException $exception
     *
     * @return stdClass
     *
     * @throws InstagramServerException
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

    /**
     * Throw required Exception
     *
     * @param \stdClass       $object
     * @param ClientException $exMessage
     *
     * @throws InstagramOAuthException|InstagramException
     */
    protected static function throwException(\stdClass $object, ClientException $exMessage)
    {
        $exception = static::createExceptionMessage($object);
        if (stripos($exception['error_type'], 'oauth') !== false) {
            throw new InstagramOAuthException(
                json_encode(['Type' => $exception['error_type'], 'Message' => $exception['error_message']]),
                $exception['error_code'],
                $exMessage
            );
        }
        throw new InstagramException(
            json_encode(['Type' => $exception['error_type'], 'Message' => $exception['error_message']]),
            $exception['error_code'],
            $exMessage
        );
    }

    /**
     * Creates Exception Message
     *
     * @param \stdClass $object
     *
     * @return array
     */
    protected static function createExceptionMessage(\stdClass $object)
    {
        $message = [];
        $message['error_type'] = isset($object->meta) ? $object->meta->error_type : $object->error_type;
        $message['error_message'] = isset($object->meta) ? $object->meta->error_message : $object->error_message;
        $message['error_code'] = isset($object->meta) ? $object->meta->code : $object->code;

        return $message;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *HelperFactory* instance.
     */
    private function __clone()
    {
        // a factory clone should never be invoked
    }

    /**
     * Private unserialize method to prevent unserializing of the *HelperFactory	*
     * instance.
     */
    private function __wakeup()
    {
    }
}
