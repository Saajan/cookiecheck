_**CookieCheck** is a simple PHP script to check whether visitors to your site have cookies enabled or not. Nothing about this project is likely to win a Turing Award anytime soon, but hey; it's simple, it works. and it saves you having to reinvent the wheel for each of your scripts that rely on cookies. And who knows, maybe one day a Turing Award recipient will be on the podium thanking CookieCheck for making it all possible! Don't worry, I  won't hold my breath..._


#####


---


### _What's Going On?_ ###
_**14/5/2008:** CookieCheck v1.1 has been released. This release includes some minor bug-fixes and enhancements. It also rolls in the v1.0 security-fix-1 patch which fixed a potentially serious security vulnerability with the default session save path._


#####


---


### _Tell Me More, Tell Me More, But You Don't Gotta Brag_ ###
**Cookies, what is it good for?** Absolutely everything! Well not quite, but cookies are a commonly used method of maintaining information about a visitor across their entire visit to your site, or even across multiple visits. There are other ways of doing this (GET query strings, hidden form fields, etc.) - and they all have their benefits and drawbacks - but cookies have become one of the most commonly used. Even PHP's built in session management functions use cookies in the background. So for most websites that need to be able to store information about the user from page to page (e.g. login information, personalisation, etc.) cookies are an important part of the web developer's arsenal. (For more information about cookies, take a look at [Wikipedia - HTTP Cookies](http://en.wikipedia.org/wiki/HTTP_cookie)).

**Do I really need to test for them?** Good question... After all most users now-days have their browsers set to automatically accept cookies. So is there any real need to explicitly test that they are enabled? Well, yes... It's just good practice to at least inform people without cookies turned on that your site won't work correctly for them. If they really like you, they might even turn cookies on. The clever developer will probably even implement a second-best solution that will work for those people without cookies enabled.

**Seems like a lot of work for just a handful of people...** Well, firstly, with the CookieCheck script it isn't a lot of work at all. Secondly, by explicitly testing upfront whether or not users have cookies enabled or not, the rest of your script can happily blunder on in the assumption that anyone who has gotten this far _has_ cookies enabled. This means for a little bit of work up top, the rest of your script is a lot simpler and doesn't need to account for the possibility of not receiving a cookie when it is expecting one.

**So how much work is involved with this cookie testing stuff anyway?** Not much at all. The script has been designed so that all you need to do is include the file `cookiecheck.php` into your script and call the function `cc_cookie_cutter()`. This function simply returns `TRUE` if cookies are turned on, and `FALSE` if they're off. Easy.

**Your stinkin' script doesn't work properly!** I said right up top that this script was simple and it works. If it isn't or doesn't, send me an email at [cookiecheck@googlegroups.com](mailto:cookiecheck@googlegroups.com) and I'll look into it. Alternatively you can use the issue tracker. I know this is just a simple script, but I do want it to work properly (if for no other reason than I am using this in another project of mine), so if you have any complaints, comments or suggestions, I want to hear about them.

**Nice idea, all you need now is a real coder to do it right...** My coding resume could be written on a post-it note (and there'd still be plenty of room for the shopping list), so if you'd like to contribute, there'll be no snobby 'stay out' from me. Again, just drop me an email or join the mailing list at [Google Groups](http://groups.google.com/group/cookiecheck).


#####


---


### _Usage Instructions_ ###

CookieCheck code is stored in the file `cookiecheck.php`. It consists of the public function `cc_cookie_cutter()` and a handful of private helper functions. There are a handful of configuration options that can be manipulated directly in the file by modifying the `define(...)` statements marked in the source code.

To use CookieCheck, simply include it into your existing script and then run the `cc_cookie_cutter()` function:

```

...
include_once(cookiecheck.php);
if (cc_cookie_cutter() == FALSE) {
    // Cookies are disabled
    echo 'Sorry, you do not have cookies enabled. Please enable them and reload this page';
    exit();
}
// Execution only reaches this line if cookies are enabled
...
```

Note that this script sends headers, and so the `cc_cookie_cutter()` function should be called before any output (including whitespace) has been sent to the browser. This may necessitate the use of PHP's output buffering functions.

#####