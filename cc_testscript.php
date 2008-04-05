<?php

/* CookieCheck - cc_testscript
 *
 * This is a simple test script for using CookieCheck
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

include_once('cookiecheck.php');
$test = cc_cookie_cutter();

?>

<html>

	<head>

		<title>.: CookieCheck :. </title>

	</head>

	<body>

		<h1>CookieCheck Test Script</h1>

		<h2>Cookies are <?php echo $test ? 'enabled' : 'disabled'; ?></h2>

		<h3>SERVER Variables</h3>
		<table>
			<tr>
				<th>Name</th>
				<th>Value</th>
			</tr>
	
			<?php
				foreach($_SERVER as $name => $value) {
					echo '<tr>';
					echo '<td>' . htmlspecialchars(strval($name)) . '</td>';
					echo '<td>' . htmlspecialchars(strval($value)) . '</td>';
					echo '</tr>';
				}
			?>
		</table>


		<h3>GET Variables</h3>
		<table>
			<tr>
				<th>Name</th>
				<th>Value</th>
			</tr>

			<?php
				foreach($_GET as $name => $value) {
					echo '<tr>';
					echo '<td>' . htmlspecialchars(strval($name)) . '</td>';
					echo '<td>' . htmlspecialchars(strval($value)) . '</td>';
					echo '</tr>';
				}
			?>
		</table>


		<h3>POST Variables</h3>
		<table>
			<tr>
				<th>Name</th>
				<th>Value</th>
			</tr>

			<?php
				foreach($_POST as $name => $value) {
					echo '<tr>';
					echo '<td>' . htmlspecialchars(strval($name)) . '</td>';
					echo '<td>' . htmlspecialchars(strval($value)) . '</td>';
					echo '</tr>';
				}
			?>
		</table>


		<h3>COOKIE Variables</h3>
		<table>
			<tr>
				<th>Name</th>
				<th>Value</th>
			</tr>

			<?php
				foreach($_COOKIE as $name => $value) {
					echo '<tr>';
					echo '<td>' . htmlspecialchars(strval($name)) . '</td>';
					echo '<td>' . htmlspecialchars(strval($value)) . '</td>';
					echo '</tr>';
				}
			?>
		</table>

		<h3>ENV Variables</h3>
		<table>
			<tr>
				<th>Name</th>
				<th>Value</th>
			</tr>

			<?php
				foreach($_ENV as $name => $value) {
					echo '<tr>';
					echo '<td>' . htmlspecialchars(strval($name)) . '</td>';
					echo '<td>' . htmlspecialchars(strval($value)) . '</td>';
					echo '</tr>';
				}
			?>
		</table>



	</body>

</html>

