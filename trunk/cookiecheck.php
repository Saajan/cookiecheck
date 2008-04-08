<?php

/* CookieCheck
 *
 * This script provides a function for checking whether or not a visitor
 * accessing one of your PHP scripts has cookies enabled.
 *
 * This script is designed to be easily included as part of an existing PHP
 * script. To use the script, simply include this file into your custom script
 * and run the command cc_cookie_cutter(). The return value of this function is
 * TRUE if cookies are enabled and FALSE if they are disabled.
 *
 * All externally visible tokens are prefixed with 'cc_', 'CC_', 'cc-', 'CC-',
 * '_cc_', '_CC_', '_cc-' or '_CC-'. Please be aware that if your script uses
 * those prefixes, naming conflicts could potentially arise.
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
 * KNOWN ISSUES:
 *  [TBD (medium priority)]
 *   Not all functions return values are error checked.
 *
 *  [TBR (low priority)]
 *   The address displayed in the browser after this script has run includes the
 *    '?cc_code=cc-sessid-#######' query string. The global values are still 
 *    available as expected, however the display in the address bar doesn't look
 *    great, and it causes issues if the site is bookmarked from a page with the
 *    '?cc_code' query string.
 *
 *
 *
 * Written by Jath Palasubramaniam
 *
 * Copyright 2008 Laden Donkey Studios. All rights reserved.
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
define('CC_SESSION_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../tmp/cc_sessions');

// CC_COOKIE_LIFE_DAYS - how many days a test is valid for; 0 for session only
define('CC_COOKIE_LIFE_DAYS', (7));

// CC_COOKIE_PATH - path on your site that the test is valid for; / means all
define('CC_COOKIE_PATH', '/');

// CC_PROTOCOL - protocol for client-server communication; http, https, etc
define('CC_PROTOCOL', 'http');





/* SETTINGS - Unlikely to need modification */

// CC_QUERY - variable name used in the GET query string for this script
define('CC_QUERY', 'cc_code');

// CC_COOKIE - name of the test cookie sent by this script
define('CC_COOKIE', 'cc_test');

// CC_SESSION_NAME - name of sessions used by this script
define('CC_SESSION_NAME', 'cc_session');

// CC_SESSION_ID_STEM - prefix to use with session ids for this script
define('CC_SESSION_ID_STEM', 'cc-sessid-');

// CC_SESSION_TIMEOUT - time in seconds before the test is aborted
define('CC_SESSION_TIMEOUT', (120));





/* FUNCTIONS - Public */

// cc_cookie_cutter - run a test to see whether cookies are enabled or not
// 	Takes no arguments
//  Returns TRUE if cookies are enabled, FALSE if they are disabled
function cc_cookie_cutter() {

	if (isset($_COOKIE[CC_COOKIE])) {

		// Cookies are enabled
		if (isset($_GET[CC_QUERY])) {
			// TODO: Error checking function returns
			// Restore globals as they were previously saved
			$old_session_settings = _cc_save_session_settings();
			_cc_initialise_session_settings();
			session_id($_GET[CC_QUERY]);
			session_start();
			_cc_restore_globals();
			session_destroy();
			_cc_restore_session_settings($old_session_settings);
			// Continue on and return TRUE
		}

		return TRUE;

	} else {

		// Cookies are either disabled or not yet tested for
		if (isset($_GET[CC_QUERY])) {
			// TODO: Error checking function returns
			// Restore globals as they were previously sent
			$old_session_settings = _cc_save_session_settings();
			_cc_initialise_session_settings();
			session_id($_GET[CC_QUERY]);
			session_start();
			_cc_restore_globals();
			session_destroy();
			_cc_restore_session_settings($old_session_settings);
			// Continue on and return FALSE as cookies are disabled
		} else {
			// TODO: Error checking function returns
			// Save globals as we are going to reload this page
			$old_session_settings = _cc_save_session_settings();
			_cc_initialise_session_settings();
			session_id(CC_SESSION_ID_STEM . strval(mt_rand()));
			session_start();
			_cc_save_globals();
			session_write_close();
			_cc_restore_session_settings($old_session_settings);
			// Send a test cookie
			setcookie(CC_COOKIE, 'true', 
				(time() + CC_COOKIE_LIFE_DAYS * 24 * 60 * 60), CC_COOKIE_PATH);
			header('Location: ' . CC_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . 
				$_SERVER['PHP_SELF'] . '?' . CC_QUERY . '=' . session_id());
			exit();
			// Do not continue; rather exit and reload the page
		}
		
		return FALSE;

	}

}





/* FUNCTIONS - Private */

// _cc_initialise_session_settings - configure the session settings
// 	Takes no arguments
//  Returns TRUE on success, FALSE on failure
function _cc_initialise_session_settings() {

	// Initialise the session save path
	$old_session_save_path = session_save_path();
	session_save_path(session_save_path() . CC_SESSION_PATH);
	$session_save_path = session_save_path();
	if (!file_exists($session_save_path)) {
		if (!mkdir($session_save_path, 0755, TRUE)) {
			session_save_path($old_session_save_path);
			return FALSE;
		}
	} else {
		if (!(is_dir($session_save_path) && is_readable($session_save_path) && 
			is_writeable($session_save_path))) {
			session_save_path($old_session_save_path);
			return FALSE;
		}
	}

	// Initialise session garbage collection
	ini_set('session.gc_maxlifetime', CC_SESSION_TIMEOUT);
	ini_set('session.gc_probability', 1);
	ini_set('session.gc_divisor', 1);

	// Initialise the use of cookies for sessions
	ini_set('session.use_cookies', 0);

	// Initialise the session name
	session_name(CC_SESSION_NAME);

	return TRUE;

}


// _cc_save_session_settings - save the default/custom session settings
// 	Takes no arguments
//  Returns an array containing the old settings
function _cc_save_session_settings() {

	// Save the session save path
	$old_session_settings['save_path'] = session_save_path();

	// Save the session garbage collection
	$old_session_settings['gc_maxlifetime'] = ini_get('session.gc_maxlifetime');
	$old_session_settings['gc_probability'] = ini_get('session.gc_probability');
	$old_session_settings['gc_divisor'] = ini_get('session.gc_divisor');

	// Save the user of cookies for sessions
	$old_session_settings['use_cookies'] = ini_get('session.use_cookies');

	// Save the session name
	$old_session_settings['name'] = session_name();

	return $old_session_settings;

}
	

// _cc_restore_session_settings - restore the default/custom session settings
// 	Takes an array containing the old settings
//  Returns TRUE on success, FALSE on error
function _cc_restore_session_settings($old_session_settings) {

	// Check that argument is valid
	if (!is_array($old_session_settings)) {
		return FALSE;
	}

	// Restore the session save path
	session_save_path($old_session_settings['save_path']);

	// Restore session garbage collection
	ini_set('session.gc_maxlifetime', $old_session_settings['gc_maxlifetime']);
	ini_set('session.gc_probability', $old_session_settings['gc_probability']);
	ini_set('session.gc_divisor', $old_session_settings['gc_divisor']);

	// Restore the use of cookies for sessions
	ini_set('session.use_cookies', $old_session_settings['use_cookies']);

	// Restore the session name
	session_name($old_session_settings['name']);

	return TRUE;

}


// _cc_save_globals - saves the values of the PHP global arrays
// 	Takes no arguments
//  Returns no value
function _cc_save_globals() {

	$_SESSION['_SESSION'] = $_SESSION;
	$_SESSION['_FILES'] = $_FILES;
	$_SESSION['_SERVER'] = $_SERVER;
	$_SESSION['_ENV'] = $_ENV;
	$_SESSION['_COOKIE'] = $_COOKIE;
	$_SESSION['_GET'] = $_GET;
	$_SESSION['_POST'] = $_POST;
	$_SESSION['_REQUEST'] = $_REQUEST;
	$_SESSION['GLOBALS'] = $GLOBALS;

	return;

}


// _cc_restore_globals - restores the values of the PHP global arrays
// 	Takes no arguments
//  Returns no value
function _cc_restore_globals() {

	$_FILES = $_SESSION['_FILES'];
	$_SERVER = $_SESSION['_SERVER'];
	$_ENV = $_SESSION['_ENV'];
	$_COOKIE = $_SESSION['_COOKIE'];
	$_GET = $_SESSION['_GET'];
	$_POST = $_SESSION['_POST'];
	$_REQUEST = $_SESSION['_REQUEST'];
	$GLOBALS = $_SESSION['GLOBALS'];
	$_SESSION = $_SESSION['_SESSION'];

	return;

}


// No trailing whitespace after the PHP close tag to avoid sending whitespace
?>
