<?php

/* CookieCheck - cookiecheck-config.php
 *
 * The configuration file for the CookieCheck system.
 *
 * This script provides a series of define statements referenced by the main
 * CookieCheck scripts. Not all define statements are relevant to all scripts.
 * Specifically, the nano and mini versions only implement a stripped down set
 * of features, and so, do not need all of the configuration options.
 *
 * All externally visible tokens (with the exception of the main CookieCheck()
 * funcion) are prefixed with 'cc_', 'CC_', 'cc-', 'CC-', '_cc_' or '_CC_'. 
 * Please be aware that if your script uses those prefixes, naming conflicts 
 * could potentially arise.
 *
 * The CookeCheck scripts send headers as part of their logic, so it is
 * important that no whitespace be sent to the browser. To facilitate this,
 * there must be no whitespace before or after the '<?php ... ?>' tags.
 *
 *
 * Written by Jath Palasubramaniam  ( jathpala <at> gmail <dot> com )
 *
 * Copyright 2008 Laden Donkey Studios. All rights reserved.
 *
 * This software is released under the terms of the MIT open source licence as
 * specified below:
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


// CC_COOKIE_LIFE_DAYS - how many days a test is valid for; 0 for session only
//  Used by all flavours of CookieCheck
define('CC_COOKIE_LIFE_DAYS', (7));

// CC_COOKIE_PATH - path on your site that the test is valid for; '/' means all
//  Used by all flavours of CookieCheck
define('CC_COOKIE_PATH', '/');

// CC_PROTOCOL - protocol for client-server communication; http, https, etc
//  Used by all flavours of CookieCheck
define('CC_PROTOCOL', 'http');

// CC_NAME - variable name used as the GET query string and cookie name
//  Used by all flavours of CookieCheck
define('CC_NAME', 'CookieCheck');




// No trailing whitespace after the PHP close tag to avoid sending whitespace
?>
