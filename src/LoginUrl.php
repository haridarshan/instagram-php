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
 * LoginUrl class
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
 * @since			September 03, 2016
 *
 * @copyright		Haridarshan Gorana
 *
 * @version			2.2.2
 */
class LoginUrl
{
    /** @var InstagramApp */
    protected $app;

    /** @var string */
    protected $callback;

    /** @var string */
    protected $state;

    /** @var array */
    protected $scopes;

    /**
     * LoginUrl constructor
     *
     * @param InstagramApp $app
     * @param string       $callback
     * @param string       $state
     * @param array        $scopes
     */
    public function __construct(InstagramApp $app, $callback, $state, $scopes)
    {
        $this->app = $app;
        $this->callback = $callback;
        $this->state = $state;
        $this->scopes = $scopes;
    }

    /**
     * Creates login url
     *
     * @return string
     */
    public function loginUrl()
    {
        $query = 'client_id=' . $this->app->getId() . '&redirect_uri=' . urlencode($this->callback) . '&response_type=code&state=' . $this->state;
        $query .= isset($this->scopes) ? '&scope=' . urlencode(str_replace(',', ' ', implode(',', $this->scopes))) : '';

        return sprintf('%s%s?%s', Constants::API_HOST, Constants::API_AUTH, $query);
    }
}
