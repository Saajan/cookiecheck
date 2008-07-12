<?php

/* CookieCheck
 *
 * This script provides a function for checking whether or not a visitor
 * accessing one of your PHP scripts has cookies enabled.
 *
 * This script is designed to be easily included as part of an existing PHP
 * script. To use the script, simply include this file into your custom script
 * and run the command cc_cookie_cutter(). The return value of this function is
 * TRUE if cookies are enabled and FALSE if they are disabled. The script also
 * sets the global value $CC_ERROR_MSG to something other than NULL if an error
 * or warning was generated during the running of cc_cookie_cutter(). In the
 * case where an error was generated, cc_cookie_cutter() will return FALSE. If a
 * warning only was generated, cc_cookie_cutter() will return normally (i.e.
 * TRUE if cookies are enabled and FALSE if cookies are disabled).
 *
 * All externally visible tokens are prefixed with 'cc_', 'CC_', 'cc-', 'CC-',
 * '_cc_' or '_CC_'. Please be aware that if your script uses those prefixes, 
 * naming conflicts could potentially arise.
 *
 * This script sends headers as part of its logic. This means that this script
 * needs to be included and the cc_cookie_cutter() function called before any
 * output (including whitespace) is sent. This may necessitate the use of PHP's
 * output control functions (ob_start, ob_flush, ob_end_flush, etc).
 *
 *
 * EXAMPLE USAGE:
 *  ...
 *  include_once(cookiecheck.php);
 *  $cookies_enabled = cc_cookie_cutter();
 *  if (!$cookies_enabled) {
 *      // Code to display a warning that cookies are unavailable
 *      ...
 *  }
 *  // Execution only reaches this point if cookies are available
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





/* SETTINGS - May need modification */

// CC_SESSION_PATH - directory to store temporary session data
//  The parent directory of the session save path should exist and be writeable
//  In the below example, a folder named '/[http_root]/../tmp' should exist and 
//   be writeable
define('CC_SESSION_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../tmp/cc_sessions');

// CC_COOKIE_LIFE_DAYS - how many days a test is valid for; 0 for session only
define('CC_COOKIE_LIFE_DAYS', (7));

// CC_COOKIE_PATH - path on your site that the test is valid for; / means all
define('CC_COOKIE_PATH', '/');

// CC_PROTOCOL - protocol for client-server communication; http, https, etc
define('CC_PROTOCOL', 'http');



/* SETTINGS - Unlikely to need modification */

// CC_QUERY - variable name used in the GET query string for this script
define('CC_QUERY', 'cc_status');

// CC_COOKIE - name of the test cookie sent by this script
define('CC_COOKIE', 'cc_test');

// CC_SESSION_NAME - name of sessions used by this script
define('CC_SESSION_NAME', 'cc_session');

// CC_SESSION_TIMEOUT - time in seconds before the test is aborted
define('CC_SESSION_TIMEOUT', (60));

// CC_SESSION_ID_STEM - prefix to use with session ids for this script
define('CC_SESSION_ID_STEM', 'cc-sessid-');



/* FUNCTIONS - Public */

// cc_cookie_cutter - run a test to see whether cookies are enabled or not
// 	Takes no arguments
//  Returns TRUE if cookies are enabled, FALSE if they are disabled
function CookieCheck() {

	if (isset($_COOKIE[CC_COOKIE])) {

		// Cookies are enabled
		if (isset($_GET[CC_QUERY])) {
			// Reload the page using the initial query string
			if (!_cc_initialise_session_settings()) {
				throw new Exception('CookieCheck Error: Unable to initialise ' .
				  'session settings');
			}
			session_id(CC_SESSION_ID_STEM . $_GET[CC_QUERY]);
			session_start();
			// Get the initial query string and prepare it for appending
			$qstring = $_SESSION['_SERVER']['QUERY_STRING'];
			$qstring = ($qstring == '' ? '': '?') . $qstring;
			// Get needed $_SERVER variables
			$http_host = $_SESSION['_SERVER']['HTTP_HOST'];
			$php_self = $_SESSION['_SERVER']['PHP_SELF'];
			session_write_close();
			// Reload the page, the session id will be propogated in the cookie
			header('Location: ' . CC_PROTOCOL . '://' . $http_host . $php_self .
				$qstring);
			exit();
			// Do not continue; rather exit and reload the page
		}
		// Restore any globals that are saved
		$old_session_settings = _cc_save_session_settings();
		if (!_cc_initialise_session_settings()) {
			throw new Exception('CookieCheck Error: Unable to initialise ' .
			  'session settings');
		}
		session_id(CC_SESSION_ID_STEM . strval($_COOKIE[CC_COOKIE]));
		session_start();
		_cc_restore_globals();
		session_destroy();
		if (!_cc_restore_session_settings($old_session_settings)) {
			throw new Exception('CookieCheck Warning: Unable to restore ' .
			  'session settings');
		}

		return TRUE;

	} else {

		// Cookies are either disabled or not yet tested for
		if (isset($_GET[CC_QUERY])) {
			// Continue on and return FALSE as cookies are disabled
		} else {
			// Append a flag to the end of the query string, indicating that a
			// test cookie has been sent.
			$qstring = $_SERVER['QUERY_STRING'] . 
				($_SERVER['QUERY_STRING'] == '' ? '' : '&') . CC_QUERY . '=' .
				'test';
			// Send a test cookie
			setcookie(CC_COOKIE, 'CookieCheck', 
				(time() + CC_COOKIE_LIFE_DAYS * 24 * 60 * 60), CC_COOKIE_PATH);
			header('Location: ' . CC_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . 
				$_SERVER['PHP_SELF'] . '?' . $qstring);
			exit();
			// Do not continue; rather exit and reload the page
		}
		
		return FALSE;

	}

}

// No trailing whitespace after the PHP close tag to avoid sending whitespace
?>
