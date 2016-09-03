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

use Haridarshan\Instagram\Exceptions\InstagramException;

/**
 * InstagramApp class
 * 
 * @library			instagram-php
 * @license 		https://opensource.org/licenses/MIT MIT
 * @link			http://github.com/haridarshan/instagram-php Class Documentation
 * @link			http://instagram.com/developer/ API Documentation
 * @author			Haridarshan Gorana 	<hari.darshan@jetsynthesys.com>
 * @since			September 03, 2016
 * @copyright		Haridarshan Gorana
 * @version			2.2.2
 */
class InstagramApp
{
	/**
     * @var string Instagram App ID.
     */
    protected $id;

    /**
     * @var string Instagram App Secret.
     */
    protected $secret;
	
	/**
     * @param string $id
     * @param string $secret
     *
     * @throws InstagramException
     */
    public function __construct($id, $secret)
    {
        if (!is_string($id)) {
            throw new InstagramException('The "client_id" must be formatted as a string.');
        }
		
		if (!is_string($secret)) {
            throw new InstagramException('The "client_secret" must be formatted as a string.');
        }
		
        $this->id = $id;
        $this->secret = $secret;
    }
	
	/**
     * Returns the app ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the app secret.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
}
