<?php

/* CookieCheck
 *
 * This script provides a function for checking whether or not a visitor
 * accessing one of your PHP scripts has cookies enabled.
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


define('CC_QUERY', 'cc_code');

define('CC_COOKIE', 'cc_test');
define('CC_COOKIE_LIFE_DAYS', (7));
define('CC_COOKIE_PATH', '/');

define('CC_PROTOCOL', 'http://');

define('CC_SESSION_PATH', '/home/jathpala/tmp/cc_sessions');
define('CC_SESSION_NAME', 'cc_session');
define('CC_SESSION_ID_STEM', 'cc-sessid-');
define('CC_SESSION_TIMEOUT', (120));



function _cc_initialise_session_settings() {

	// Initialise the session save path
	$old_session_settings['save_path'] = session_save_path();
	session_save_path(session_save_path() . CC_SESSION_PATH);
	$session_save_path = session_save_path();
	if (!file_exists($session_save_path)) {
		if (!mkdir($session_save_path, 0660, TRUE)) {
			session_save_path($old_session_settings['save_path']);
			return NULL;
		}
	} else {
		if (!(is_dir($session_save_path) && is_readable($session_save_path) && is_writeable($session_save_path))) {
			session_save_path($old_session_settings['save_path']);
			return NULL;
		}
	}

	// Initialise session garbage collection
	$old_session_settings['gc_maxlifetime'] = ini_get('session.gc_maxlifetime');
	$old_session_settings['gc_probability'] = ini_get('session.gc_probability');
	$old_session_settings['gc_divisor'] = ini_get('session.gc_divisor');
	ini_set('session.gc_maxlifetime', CC_SESSION_TIMEOUT);
	ini_set('session.gc_probability', 1);
	ini_set('session.gc_divisor', 1);

	// Initialise the use of cookies for sessions
	$old_session_settings['use_cookies'] = ini_get('session.use_cookies');
	ini_set('session.use_cookies', 0);

	// Initialise the session name
		$session_id = session_id();

	$old_session_settings['name'] = session_name();
	session_name(CC_SESSION_NAME);

	return $old_session_settings;

}

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

	// Restore the session id
	session_name($old_session_settings['id']);

	return TRUE;echo $_SERVER['HTTP_HOST'];

}


function _cc_save_globals() {

	$_SESSION['_SESSION'] = $_SESSION;
	$_SESSION['_SERVER'] = $_SERVER;
	$_SESSION['_ENV'] = $_ENV;
	$_SESSION['_COOKIE'] = $_COOKIE;
	$_SESSION['_GET'] = $_GET;
	$_SESSION['_POST'] = $_POST;
	$_SESSION['_FILES'] = $_FILES;
	$_SESSION['_REQUEST'] = $_REQUEST;
	$_SESSION['GLOBALS'] = $GLOBALS;

}


function _cc_restore_globals() {

	$_SERVER = $_SESSION['_SERVER'];
	$_ENV = $_SESSION['_ENV'];
	$_COOKIE = $_SESSION['_COOKIE'];
	$_GET = $_SESSION['_GET'];
	$_POST = $_SESSION['_POST'];
	$_FILES = $_SESSION['_FILES'];
	$_REQUEST = $_SESSION['_REQUEST'];
	$GLOBALS = $_SESSION['GLOBALS'];
	$_SESSION = $_SESSION['_SESSION'];

}


function cc_cookie_cutter() {

	if (isset($_COOKIE[CC_COOKIE])) {

		// Cookies are enabled - report success after restoring globals if requried
		if (isset($_GET[CC_QUERY])) {
			// Restore globals
			_cc_initialise_session_settings();
			session_id($_GET[CC_QUERY]);
			session_start();
			$old_session_settings = $_SESSION['old_session_settings'];
			_cc_restore_globals;
			session_destroy();
			_cc_restore_session_settings($old_session_settings);
		}

		return TRUE;

	} else {

		// Cookies are either not enabled, or not yet sent - either send it, or report failure
		if (!isset($_GET[CC_QUERY])) {
			// Send a test cookie
			$old_session_settings = _cc_initialise_session_settings();
			session_id(CC_SESSION_ID_STEM . strval(mt_rand()));
			$session_id = session_id();
			session_start();
			$address = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			_cc_save_globals;
			$_SESSION['old_session_settings'] = $old_session_settings;
			session_write_close();
			setcookie(CC_COOKIE, 'true', (time() + CC_COOKIE_LIFE_DAYS * 24 * 60 * 60), CC_COOKIE_PATH);
			$header = 'Location: ' . CC_PROTOCOL . $address . '?' . CC_QUERY . '=' . $session_id;
			header($header);
			exit();
		}


		return FALSE;

	}

}

?>
