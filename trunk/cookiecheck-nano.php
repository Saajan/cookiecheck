<?php

/* CookieCheck - cookiecheck-nano.php
 *
 * A simple PHP script to check whether or not visitors to a web page have
 * cookies enabled or not.
 *
 * This script provides a single function, CookieCheck(), which simply returns
 * TRUE or FALSE to reflect whether or not cookies are available.
 *
 * The nano version of this function is heavily simplified such that it discards
 * any information passed into the script via GET or POST methods. After the
 * function returns to the user, any GET or POST data specified by the visitor
 * will no longer be available for use. This version of CookieCheck is designed
 * for pages that do not expect any of this data. If you do need to use GET or
 * POST data, then consider either the standard or mini versions of this script.
 * The benefit of this script over the standard and mini versions is that it is
 * the smallest of the trio, and thus, slightly faster to run (though all three
 * versions are fairly small and should run fairly quickly).
 *
 * The CookieCheck function is designed to be easily included as part of an
 * existing PHP script. Simple include the file cookiecheck-nano.php and then
 * call CookieCheck() when you want to test for cookies. This function will
 * reload the page (potentially twice), and eventually return with a value of
 * either TRUE or FALSE depending on the availability of cookies. After running,
 * the global variables set by the server will be altered (in particular, there
 * will be no GET or POST data available and the query string will be empty).
 *
 * Any errors raised by this function are returned to the caller via exceptions.
 *
 * All externally visible tokens (with the exception of the main CookieCheck()
 * funcion) are prefixed with 'cc_', 'CC_', 'cc-', 'CC-', '_cc_' or '_CC_'. 
 * Please be aware that if your script uses those prefixes, naming conflicts 
 * could potentially arise.
 *
 * This script sends headers as part of its logic. This means that this script
 * needs to be included and the CookieCheck() function called before any
 * output (including whitespace) is sent. This may necessitate the use of PHP's
 * output control functions (ob_start, ob_flush, ob_end_flush, etc).
 *
 *
 * EXAMPLE USAGE:
 *  ...
 *  include_once(cookiecheck-nano.php);
 *  if (CookieCheck()) {
 *		// Code to execute if cookies are enabled.
 *		...
 *	} else {
 *		// Code to exectue if cookies are not enabled.
 *      ...
 *  }
 *  ...
 *
 *
 * Written by Jath Palasubramaniam
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
 *
 */

/* SETTINGS */
// CC_COOKIE_LIFE_DAYS - how many days a test is valid for; 0 for session only
define('CC_COOKIE_LIFE_DAYS', (7));

// CC_COOKIE_PATH - path on your site that the test is valid for; / means all
define('CC_COOKIE_PATH', '/');

// CC_PROTOCOL - protocol for client-server communication; http, https, etc
define('CC_PROTOCOL', 'http');

// CC_NAME - variable name used as the GET query string and cookie name
// (This setting is unlikely to need modification)
define('CC_NAME', 'CookieCheck');


/* FUNCTIONS */
// CookieCheck() - run a test to see whether cookies are enabled or not
// 	Takes no arguments
//  Returns TRUE if cookies are enabled, FALSE if they are disabled
function CookieCheck() {

	if (isset($_COOKIE[CC_NAME])) {

		// Cookies are enabled
		if ($_SERVER['QUERY_STRING'] == CC_NAME) {
			// Cookies were just tested for, reload the page without any query 
			// to prevent the user from having CC_NAME as a query string in the
			// rest of his script
			header('Location: ' . CC_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . 
			  $_SERVER['PHP_SELF']);
			exit();
			// Do not continue; rather exit and reload the page
		}
		// Return TRUE to indicate that cookies are available

		return TRUE;
	} else {

		// Cookies are either disabled or not yet tested for
		if ($_SERVER['QUERY_STRING'] != CC_NAME) {
			// Cookies have not yet been tested for, so test now
			// Send a test cookie
			setcookie(CC_NAME, CC_NAME, 
				(time() + CC_COOKIE_LIFE_DAYS * 24 * 60 * 60), CC_COOKIE_PATH);
			// Reload the page with a special GET query string to indicate that 
			// a test cookie has been sent
			header('Location: ' . CC_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . 
				$_SERVER['PHP_SELF'] . '?' . CC_NAME);
			exit();
			// Do not continue; rather exit and reload the page
		}
		// Return FALSE to indicate that cookes are not available

		return FALSE;
	}

}

// No trailing whitespace after the PHP close tag to avoid sending whitespace
?>
