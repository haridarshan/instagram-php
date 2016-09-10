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

/**
 * Constant class
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
class Constants
{
    /** Library Version */
    const VERSION = '2.2.2';

    /** API End Point */
    const API_VERSION = 'v1';

    /** API End Point */
    const API_HOST = 'https://api.instagram.com/';

    /** OAuth2.0 Authorize API End Point */
    const API_AUTH = 'oauth/authorize';

    /** OAuth Access Token API End Point */
    const API_TOKEN = 'oauth/access_token';
}
