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
use GuzzleHttp\Psr7\Response;
use Haridarshan\Instagram\Exceptions\InstagramResponseException;

class InstagramResponse
{
    /** @var int $status_code */
    protected $statusCode;
	
    /** @var string $protocol */
    protected $protocol = '1.1';
	
    /** @var array $headers */
    protected $headers = [];
	
    /** @var bool */
    protected $isPagination = false;
	
    /** @var object */
    protected $pagination;
    
    /** @var bool */
    protected $isMetaData = false;
	
    /** @var object */
    protected $metaData;
	
    /** @var object */
    protected $data;
	
    /** @var object $body */
    protected $body;
	
    /**
	 * InstagramResponse Entity
	 * 
	 * @param Response $response
	 * 
	 * @throws InstagramResponseException
	 */
    public function __construct(Response $response)
    {
        if (!$response instanceof Response) {
            throw new InstagramResponseException('Bad Request: Response is not valid instance of GuzzleHttp\Psr7\Response', 404); 
        }
        $this->setParams($response);
    }
    
    /**
	 * Set Values to the class members
	 * 
	 * @param Response $response
	 * 
	 * @return void
	 */
    private function setParams(Response $response)
    {
        $this->protocol = $response->getProtocolVersion();
        $this->statusCode = (int) $response->getStatusCode();
        $this->headers = $response->getHeaders();
        $this->body = json_decode($response->getBody()->getContents());
        $this->extractBodyParts();
    }
	
	/**
	 * Extract Body Parts from the response
	 * 
	 * @return void
	 */
    private function extractBodyParts()
    {
        if (isset($this->body->pagination)) {
            $this->isPagination = true;
            $this->pagination = $this->body->pagination;
        }
		
        if (isset($this->body->meta)) {
            $this->isMetaData = true;
            $this->metaData = $this->body->meta;
        }
			
        $this->data = $this->body->data;	
    }
    
    /**
	 * Get response
	 * 
	 * @return object|string 
	 */
    public function getBody()
    {
        return $this->body;
    }
    
    /** 
	 * Get Status Code
	 * 
	 * @return int
	 */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    /**
	 * Get specific header
	 * 
	 * @param string $header
	 * 
	 * @retrun string 
	 */
    public function getHeader($header)
    {
        return isset($this->headers[$header]) ? $this->headers[$header] : [];
    }
	
    /**
	 * Get all headers
	 * 
	 * @retrun array 
	 */
    public function getHeaders()
    {
        return $this->headers;
    }
	
    /**
	 * Get data from body
	 * 
	 * @return object
	 */
    public function getData()
    {
        return $this->data;
    }
	
    /**
	 * Get Meta data
	 * 
	 * @return object
	 */
    public function getMetaData()
    {
        return $this->metaData;
    }
	
    /**
	 * Get Meta data
	 * 
	 * @return object
	 */
    public function getPagination()
    {
        return $this->pagination;
    }
	
    /**
	 * Is Meta Data Present
	 * 
	 * @return bool
	 */
    public function isMetaDataSet()
    {
        return $this->isMetaData;
    }
	
    /**
	 * Is Pagination present
	 * 
	 * @return bool
	 */
    public function isPaginationSet()
    {
        return $this->isPagination;
    }
	
    /**
	 * Get Protocol version
	 * 
	 * @return string
	 */
    public function getProtocol()
    {
        return $this->protocol;
    }
}
