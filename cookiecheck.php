<?php

/* CookieCheck
 *
 * This is a simple script for testing whether the user has cookies enabled.
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
 *
 * USAGE:
 *   - Edit the definitions below as desired - in particular, change
 *     CC_RETURN_PAGE to be whatever page you want to take control after this 
 *     script has run (usually the page that this script is called from).
 *   - Upload the file to your webserver
 *   - Call the script by using something similar to:
 *        header('Location: cookie_check.php');    OR
 *        header('Location: cookie_check.php?action=set');
 *     in calling page (e.g. index.php).
 *   - After completing, this script will reload the specified CC_RETURN_PAGE
 *     with either:
 *        $_GET['cookie_check'] == 'false' if cookies are disabled
 *        $_COOKIE['cookie_check'] == 'true' if cookies are enable (in the
 *          default setup of this script)
 *        $_GET['cookie_check'] == 'true' if cookies are enabled (and this
 *          script is modified to explicitly return success
 *        $_GET['cookie_check'] == 'error' if an error occured
 */

define('CC_COOKIE_NAME', 'cookie_check');
define('CC_COOKIE_EXPIRY_DAYS', 3);
define('CC_COOKIE_PATH', '/');
define('CC_RETURN_PAGE', 'index.php');
define('CC_SUCCESS_CODE', '');
//define('CC_SUCCESS_CODE', '?cookie_check=true');
define('CC_FAILURE_CODE', '?cookie_check=false');
define('CC_ERROR_CODE', '?cookie_check=error');


if (isset($_GET['action'])) {
	// Perform the action specified by the GET method variable 'action'.
	if ($_GET['action'] == 'set') {
		// The test cookie has not yet been set; set it and rerun this script.
		setcookie(CC_COOKIE_NAME, 'true', time() + 
			(CC_COOKIE_EXPIRY_DAYS * 24 * 60 * 60), CC_COOKIE_PATH);
		header('Location: '. $_SERVER['PHP_SELF'] . '?action=get');
	} elseif ($_GET['action'] == 'get') {
		// The test cookie has been set; check that a cookie has been returned.
		if (isset($_COOKIE[CC_COOKIE_NAME])) {
			//Cookies are enabled.
			//setcookie(CC_COOKIE_NAME, 'false', time() - (365 * 24 * 60 * 60),
			// CC_COOKIE_PATH);
			header('Location: ' . CC_RETURN_PAGE . CC_SUCCESS_CODE);
			// The current implementation signals success to the return page by 
			//  leaving the test cookie available to the return page.
			// To explicitly signal using a GET method, change the definition of
			//  SUCCESS_CODE to indicate a success query string.
			// If explicitly signalling success, the setcookie() statement above
			//  can be uncommented to force deletion of the test cookie.
		} else {
			//Cookies are disabled.
			header('Location: ' . CC_RETURN_PAGE . CC_FAILURE_CODE);
		}
	} else {
		// An unknown action has been specified.
		header('Location: ' . CC_RETURN_PAGE . CC_ERROR_CODE);		;
	}
} else {
	// Reload this page, specifing the action as set.
	header('Location: '. $_SERVER['PHP_SELF'] . '?action=set');
}

?>
